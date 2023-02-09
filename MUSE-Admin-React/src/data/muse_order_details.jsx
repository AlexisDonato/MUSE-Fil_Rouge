import axios from "axios";

export default async function OrderDetails() {
  // With function 'fetch'
  // const order_details = (await (await fetch("https://127.0.0.1:8000/api/order_details")).json())["hydra:member"];
  // console.log(order_details);

  // With 'axios' ("proxy" : "http://localhost:8000" is needed to be written in 'package.json)
  const order_details = (
    await axios.get("/api/order_details", {
      headers: {
        "Accept": "application/json"
      }
    })).data;

  return (
    order_details.map((item) => (
      {
        id: item?.id,
        productId: item?.productId,
        quantity: item?.quantity,
        cart: item?.cart.clientOrderId,
        product: item?.product.name,
        subTotal: item?.subTotal,
      }
    )
    )
  )
};