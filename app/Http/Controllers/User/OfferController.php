<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProductOffer;
use App\Http\Requests\StoreProductOfferRequest;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index()
    {
        $offers = ProductOffer::active()->with('user')->latest()->paginate(10);
        return view('user.offers.index', compact('offers'));
    }

    public function show($id)
    {
        $offer = ProductOffer::with('user')->findOrFail($id);

        // Allow: 
        // - Public users to see only ACTIVE (approved + not expired) offers
        // - Owners to see their own offers (even pending/rejected/expired)
        if (auth()->check() && $offer->user_id === auth()->id()) {
            // Owner can view any of their offers
        } else {
            // Public users: only active offers
            if ($offer->status !== 'approved' || $offer->is_expired) {
                abort(404);
            }
        }

        // Increment views only for public (non-owner) views
        if (!auth()->check() || $offer->user_id !== auth()->id()) {
            $offer->increment('views');
        }

        return view('user.offers.show', compact('offer'));
    }

    public function myOffers()
    {
        $offers = ProductOffer::where('user_id', Auth::id())
            ->with('approvedBy')
            ->latest()
            ->paginate(10);
            
        return view('user.offers.my-offers', compact('offers'));
    }

    public function create()
    {
        if (Auth::user()->isSuspended()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Your account is suspended. You cannot post offers.');
        }
        
        return view('user.offers.create');
    }

    public function store(StoreProductOfferRequest $request)
    {
        if (Auth::user()->isSuspended()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is suspended.'
            ], 403);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('product-offers', 'public');
            $data['product_image'] = $path;
        }

        $offer = ProductOffer::create($data);

        // Log activity
        UserActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'offer_posted',
            'reason' => 'New product offer created',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Offer posted successfully and awaiting admin approval.',
            'redirect' => route('user.offers.my-offers')
        ]);
    }

    public function share($id)
    {
        $offer = ProductOffer::active()->findOrFail($id);
        
        $shareLinks = [
            'whatsapp' => 'https://wa.me/?text=' . urlencode("Check out this offer: {$offer->product_name} - " . route('offers.show', $offer->id)),
            'email' => 'mailto:?subject=' . urlencode($offer->product_name) . '&body=' . urlencode("Check out this offer: " . route('offers.show', $offer->id)),
            'link' => route('offers.show', $offer->id),
        ];
        
        return response()->json([
            'success' => true,
            'links' => $shareLinks
        ]);
    }
}