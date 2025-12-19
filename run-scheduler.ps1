# run-scheduler.ps1
$artisanPath = "C:\Users\Dell\Downloads\aryan\job-portal\artisan"
$phpPath = "C:\xampp\php\php.exe"

while ($true) {
    & $phpPath $artisanPath schedule:run
    Start-Sleep -Seconds 60
}