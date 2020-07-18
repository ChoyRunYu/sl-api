Set fsoobj = CreateObject("Scripting.FileSystemObject")
 DriversInfo = GetDriversInfo
 DriversInfo = Replace(DriversInfo, "|", vbCrLf)
 sReturn = "硬盘信息：" & vbCrLf & DriversInfo
 Wscript.Echo sReturn
Function GetDriversInfo()

   GetDriversInfo = ""
   Set drvObj = fsoobj.Drives
   For Each D In drvObj
       Err.Clear
       If D.DriveLetter <> "A" Then
           If D.isReady Then
               GetDriversInfo = GetDriversInfo & "分区:" & D.DriveLetter & vbCrLf
               GetDriversInfo = GetDriversInfo & "可用空间:" & cSize(D.FreeSpace) & vbCrLf
               GetDriversInfo = GetDriversInfo & "总大小:" & cSize( D.TotalSize) & vbCrLf
               GetDriversInfo = GetDriversInfo & "使用率 :" & FormatPercent(((D.TotalSize-D.FreeSpace)/D.TotalSize),2)  & vbCrLf
               GetDriversInfo = GetDriversInfo & "|"
             Else
           End If
         Else
       End If
   Next
End Function

 Function cSize(tSize)

     If tSize >= 1073741824 Then
         cSize = Int((tSize / 1073741824) * 1000) / 1000 & " GB"
       ElseIf tSize >= 1048576 Then
         cSize = Int((tSize / 1048576) * 1000) / 1000 & " MB"
       ElseIf tSize >= 1024 Then
         cSize = Int((tSize / 1024) * 1000) / 1000 & " KB"
       Else
         cSize = tSize & "B"
     End If

End Function