@echo off
start "Laravel" cmd /c "php artisan serve"
timeout /t 3 /nobreak >nul
"%USERPROFILE%\Desktop\opencode\cloudflared.exe" tunnel --config cloudflared-config.yml
pause
