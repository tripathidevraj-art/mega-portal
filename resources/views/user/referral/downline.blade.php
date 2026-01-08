@extends('layouts.app')

@section('title', 'My Referral Tree')

@section('content')
<div class="body genealogy-body genealogy-scroll">
    <div class="genealogy-tree">
        <ul>
            @include('user.referral.tree-node', ['node' => $tree, 'level' => 1])
        </ul>
    </div>
</div>

<style>
/*----------------genealogy-scroll----------*/
.genealogy-scroll::-webkit-scrollbar {
    width: 5px;
    height: 8px;
}
.genealogy-scroll::-webkit-scrollbar-track {
    border-radius: 10px;
    background-color: #e4e4e4;
}
.genealogy-scroll::-webkit-scrollbar-thumb {
    background: #212121;
    border-radius: 10px;
    transition: 0.5s;
}
.genealogy-scroll::-webkit-scrollbar-thumb:hover {
    background: #d5b14c;
    transition: 0.5s;
}

/*----------------genealogy-tree----------*/
.genealogy-body{
    white-space: nowrap;
    overflow-y: hidden;
    padding: 50px;
    min-height: 500px;
    padding-top: 10px;
    text-align: center;
    background: #f8f9fa;
}
.genealogy-tree{
  display: inline-block;
}
.genealogy-tree ul {
    padding-top: 20px; 
    position: relative;
    padding-left: 0px;
    display: flex;
    justify-content: center;
}
.genealogy-tree li {
    float: left; 
    text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
}
.genealogy-tree li::before, .genealogy-tree li::after{
    content: '';
    position: absolute; 
    top: 0; 
    right: 50%;
    border-top: 2px solid #ccc;
    width: 50%; 
    height: 18px;
}
.genealogy-tree li::after{
    right: auto; 
    left: 50%;
    border-left: 2px solid #ccc;
}
.genealogy-tree li:only-child::after, .genealogy-tree li:only-child::before {
    display: none;
}
.genealogy-tree li:only-child{ 
    padding-top: 0;
}
.genealogy-tree li:first-child::before, .genealogy-tree li:last-child::after{
    border: 0 none;
}
.genealogy-tree li:last-child::before{
    border-right: 2px solid #ccc;
    border-radius: 0 5px 0 0;
}
.genealogy-tree li:first-child::after{
    border-radius: 5px 0 0 0;
}
.genealogy-tree ul ul::before{
    content: '';
    position: absolute; 
    top: 0; 
    left: 50%;
    border-left: 2px solid #ccc;
    width: 0; 
    height: 20px;
}
.genealogy-tree li a{
    text-decoration: none;
    color: #666;
    font-family: arial, verdana, tahoma;
    font-size: 11px;
    display: inline-block;
    border-radius: 5px;
}
.genealogy-tree li a:hover, 
.genealogy-tree li a:hover+ul li a {
	background: #c8e4f8;
	color: #000;
}
.genealogy-tree li a:hover+ul li::after, 
.genealogy-tree li a:hover+ul li::before, 
.genealogy-tree li a:hover+ul::before, 
.genealogy-tree li a:hover+ul ul::before{
    border-color: #fbba00;
}

/*--------------member-card-design----------*/
.member-view-box{
    padding-bottom: 10px;
    text-align: center;
    border-radius: 4px;
    position: relative;
    border: 1px solid #e4e4e4;
    background: white;
    width: 140px;
}
.member-image{
    padding:10px;
    width: 120px;
    position: relative;
}
.member-image img{
    width: 100px;
    height: 100px;
    border-radius: 6px;
    background-color: #fff;
    z-index: 1;
    object-fit: cover;
}
.member-header {
    padding: 5px 0;
    text-align: center;
    background: #345;
    color: #fff;
    font-size: 14px;
    border-radius: 4px 4px 0 0;
}
.member-footer {
    text-align: center;
    padding: 8px 0;
}
.member-footer div.name {
    color: #000;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
}
.member-footer div.downline {
    color: #666;
    font-size: 12px;
    font-weight: bold;
}
</style>

@push('scripts')
<script>
$(function () {
    $('.genealogy-tree ul').hide();
    $('.genealogy-tree>ul').show();
    $('.genealogy-tree ul.active').show();
    
    $('.genealogy-tree li').on('click', function (e) {
        var children = $(this).find('> ul');
        if (children.is(":visible")) {
            children.hide('fast').removeClass('active');
        } else {
            children.show('fast').addClass('active');
        }
        e.stopPropagation();
    });
});

</script>
@endpush
@endsection