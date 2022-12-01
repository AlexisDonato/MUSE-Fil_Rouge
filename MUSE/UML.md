```mermaid
classDiagram

class Address{
PK - id
name
country
zipcode
city
pathType
pathNumber
user
billingAddress
deliveryAddress
}

class Category{
PK - id
name
parentCategory
product
image
}

Category -- self
Category -- Product
class Contact{
PK - id
name
email
message
user
subject
enquiryDate
}

class Cart{
PK - id
clientOrderId
validated
orderDetails
user
orderDate
shipped
shipmentDate
carrier
carrierShipmentId
total
billingAddress
deliveryAddress
additionalDiscountRate
invoice
}

Cart -- OrderDetails
class OrderDetails{
PK - id
productId
quantity
cart
product
subTotal
}

class ResetPasswordRequest{
PK - id
user
}

ResetPasswordRequest -- User
class Supplier{
PK - id
name
}

class Product{
PK - id
name
price
description
content
discount
discountRate
quantity
image
image1
image2
supplier
category
}

class User{
PK - id
email
roles
password
userName
userLastname
birthdate
phoneNumber
isVerified
carts
registerDate
vat
pro
proCompanyName
proDuns
proJobPosition
address
}

User -- Cart
User -- Address

```