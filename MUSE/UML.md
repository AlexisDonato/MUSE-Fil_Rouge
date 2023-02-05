```mermaid
classDiagram

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
coupon
}

Cart -- OrderDetails
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

class Contact{
PK - id
name
email
message
user
subject
enquiryDate
}

class Coupon{
PK - id
code
discountRate
validated
cart
}

Coupon -- Cart
class Supplier{
PK - id
name
}

class OrderDetails{
PK - id
productId
quantity
cart
product
subTotal
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
class User{
PK - id
email
roles
password
userName
userLastname
birthdate
phoneNumber
verified
carts
registerDate
vat
pro
proCompanyName
proDuns
proJobPosition
address
agreeTerms
}

User -- Cart
User -- Address
class ResetPasswordRequest{
PK - id
user
}

ResetPasswordRequest -- User

```