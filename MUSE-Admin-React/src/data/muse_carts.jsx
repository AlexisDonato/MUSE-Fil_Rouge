import axios from "axios";

export default async function Carts() {
  let carts = [];
  try {
    const response = await axios.get("/api/carts", {
      headers: {
        "Accept": "application/json"
      }
    });
    carts = response.data;
  } catch (error) {
    console.error(error);
  }
  return carts
  .sort((a, b) => new Date(b.orderDate) - new Date(a.orderDate))
    .map((item) => (
      {
        id: item?.id,
        clientOrderId: item?.clientOrderId,
        validated: item?.validated,
        orderDetails: item?.orderDetails,
        user: item?.user,
        orderDate: item?.orderDate,
        total: item?.total,
        shipped: item?.shipped,
        shipmentDate: item?.shipmentDate,
        billingAddress: item?.billingAddress,
        deliveryAddress: item?.deliveryAddress,
        additionalDiscountRate: item?.additionalDiscountRate,
        invoice: item?.invoice,
        coupon: item?.coupon,
        carrier: item?.carrier,
        carrierShipmentId: item?.carrierShipmentId,
      }
  ));
}
