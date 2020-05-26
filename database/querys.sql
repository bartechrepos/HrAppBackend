/** TBL_HR_EmployeeMasterFile **/
SELECT TOP (100) *
  FROM [BarTechImis].[dbo].[TBL_HR_EmployeeMasterFile] where Company=1;

EXEC [dbo].[SP_HR_Vacations_Insert] @PhoneNumber = '0100',
@Type = '02003-BFAE6B9E-44DB-4C22-BA93-67B5CE2BD6AC',
@Dayes = 1,
@SerialNo = '',
@FromDate = '2020-05-25',
@ToDate = '2020-05-25';

EXEC SP_HR_EmployeeAttendanceLog_Insert @PhoneNumber='01101', @InOutMode=0, @Date='2020-05-21 00:11:11' ;

SELECT TOP (1000) *
  FROM [BarTechImis].[dbo].[TBL_HR_EmployeeAttendanceLog];
