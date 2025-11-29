@echo off
REM ============================================================
REM Live-Tv-Server-theme Git Upload Script
REM Author: Md. Sohag Rana
REM Date: 2025-11-29
REM ============================================================

cd /d "C:\Users\Md Sohag Rana\Downloads\Live-Tv-Server-theme"

if not exist ".git" (
    echo Initializing new Git repository...
    git init
    git branch -M main
    git remote add origin https://github.com/sohag1192/Live-Tv-Server-theme.git
)

REM Sync with remote
git fetch origin main
git merge origin/main

REM Stage all files (tracked + untracked)
echo Adding all files...
git add -A

REM Commit with timestamp
set datetime=%date% %time%
git commit -m "Auto commit on %datetime%"

REM Push to main branch
echo Pushing to GitHub main branch...
git push origin main

echo ============================================================
echo Done! All files have been committed and pushed to GitHub.
echo ============================================================
pause