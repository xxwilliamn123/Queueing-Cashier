' Launch FastAPI print service at logon. Uses this script's folder (no hardcoded path).
Option Explicit
Dim fso, WshShell, scriptDir, batPath, q
q = Chr(34)
Set fso = CreateObject("Scripting.FileSystemObject")
Set WshShell = CreateObject("WScript.Shell")
scriptDir = fso.GetParentFolderName(WScript.ScriptFullName)
batPath = scriptDir & "\start-fastapi-background.bat"
' cmd /c "path with spaces\file.bat"
WshShell.Run "cmd /c " & q & batPath & q, 0, False
Set WshShell = Nothing
Set fso = Nothing
