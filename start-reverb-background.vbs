Set WshShell = CreateObject("WScript.Shell")
REM Run batch file silently (0 = hidden window)
WshShell.Run "cmd /c ""C:\xampp\htdocs\Queueing Cashier\start-reverb-background.bat""", 0, False
Set WshShell = Nothing
