## getSingleSpecificationList
`// ANCHOR[id= 
] getSingleSpecificationList explain`
```
USE [BarTechImis]
GO
DECLARE	@return_value int
EXEC	@return_value = [dbo].[SP_SpecificationElementDet_FindAll]
		@HeadId = N'01001-76FA4553-BEB5-489B-9C7B-C3F598FA3EFE'
SELECT	'Return Value' = @return_value
GO
ID
Company
Branch
GUID
HeadId
ArabicDescription
EnglishDescription
CreatedBy
CreatedDate
UpdatedBy
LastUpdate
Deleted
DeletedBy
DeletedDate
CreatedMacNo
Revision
SyncGUID
rowguid
```
