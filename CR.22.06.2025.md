- [ ] Category uniqness check (to avoid multiple duplicates)
- [ ] Validation rules for product creation and redundant fields. This payload is perfectly valid.
```
{
  "name": "Pencil",
  "description": "string",
  "price": "99.00",
  "stockQuantity": -100,
  "SKU": "",
  "CategoryId": 100,
  "created_at": "2025-06-22T21:56:11.040Z",
  "updated_at": "2025-06-22T21:56:11.041Z",
  "wearhouse_id": 100,
  "sKU": "",
  "categoryId": 99,
  "createdAt": "2025-06-22T21:56:11.041Z",
  "updatedAt": "2025-06-22T21:56:11.041Z",
  "wearhouseId": 121
}
```

- [ ] warehouse should be selected by id not by title
```
{
  "product_id": 1,
  "warehouse_title": "Warehouse 1",
  "quantity": 100,
  "comment": ""
}
```
- [ ] Reservation should receive an object with a structure like:
```
{warehouse_id: 123, comment: "...", products: [{"id" : 123, "quantity" : 20}]}
```
- [ ] NO users or ROLES, it is an internal api, it can only track user ID's (ex: reservation created_by_user_id) but it shouldn't rely on it and shouldn't perform any checks. User and ACL should be obtained from Laravel project or another API through JWT token.(not now, we'll implement this integration later)
- [ ] strange field naming - `warehouse`
```
{
  "Warehouse": "string",
  "description": "string",
  "location": "string",
  "warehouse": "string"
}
```
- [ ] Controllers can be removed
- [ ] Any entity or DTO in Symfony should rely on camelCase naming in both properties and method names
