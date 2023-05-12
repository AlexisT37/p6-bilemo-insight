# Check if folder exists and remove subfolder
if (Test-Path "C:\websites\p7-bilemo\var\cache") {
    Set-Location "C:\websites\p7-bilemo\var\cache"
    if (Test-Path "dev") { Remove-item -r dev } else { Write-Host "Folder dev is already deleted" }
    if (Test-Path "test") { Remove-item -r test } else { Write-Host "Folder test is already deleted" }
} else { Write-Host "Folder C:\websites\p7-bilemo\var\cache does not exist" }

# Check if folder exists and remove log files
if (Test-Path "C:\websites\p7-bilemo\var\log") {
    Set-Location "C:\websites\p7-bilemo\var\log"
    if (Test-Path "dev.log") { Remove-item -r dev.log } else { Write-Host "File dev.log is already deleted" }
    if (Test-Path "test.log") { Remove-item -r test.log } else { Write-Host "File test.log is already deleted" }
} else { Write-Host "Folder C:\websites\p7-bilemo\var\log does not exist" }
