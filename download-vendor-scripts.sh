@echo off
REM Script to download vendor JavaScript libraries for Azka Garden
REM @updated 2025-07-30 05:31:22 by mulyadafa

echo === Azka Garden - Vendor Scripts Downloader ===
echo Creating vendor directory...
mkdir public\js\vendor 2>nul
mkdir resources\js\vendor 2>nul

echo Downloading lodash.min.js...
curl -o public\js\vendor\lodash.min.js https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js
copy public\js\vendor\lodash.min.js resources\js\vendor\ >nul

echo Downloading alpine.min.js...
curl -o public\js\vendor\alpine.min.js https://cdn.jsdelivr.net/npm/alpinejs@3.12.3/dist/cdn.min.js
copy public\js\vendor\alpine.min.js resources\js\vendor\ >nul

echo Downloading chart.min.js...
curl -o public\js\vendor\chart.min.js https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js
copy public\js\vendor\chart.min.js resources\js\vendor\ >nul

echo Downloading flatpickr.min.js...
curl -o public\js\vendor\flatpickr.min.js https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js
copy public\js\vendor\flatpickr.min.js resources\js\vendor\ >nul

REM Optional: Download CSS for flatpickr
echo Downloading flatpickr.min.css...
curl -o public\js\vendor\flatpickr.min.css https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css
copy public\js\vendor\flatpickr.min.css resources\js\vendor\ >nul

echo Downloading axios.min.js...
curl -o public\js\vendor\axios.min.js https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js
copy public\js\vendor\axios.min.js resources\js\vendor\ >nul

echo All vendor scripts downloaded successfully.
echo Current Date and Time (UTC): 2025-07-30 05:31:22
echo User: mulyadafa
pause
