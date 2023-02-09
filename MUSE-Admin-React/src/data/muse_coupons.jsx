import axios from "axios";

export default async function Coupons() {
  // With function 'fetch'
  // const coupons = (await (await fetch("https://127.0.0.1:8000/api/coupons")).json())["hydra:member"];
  // console.log(coupons);

  // With 'axios' ("proxy" : "http://localhost:8000" is needed to be written in 'package.json)
  const coupons = (
    await axios.get("/api/coupons", {
      headers: {
        "Accept": "application/json"
      }
    })).data;
    console.log(coupons);
  return (
    coupons.map((item) => (
      {
        id: item?.id,
        code: item?.code,
        discountRate: item?.discountRate,
        validated: item?.validated,
        cart: item?.cart,
      }
    )
    )
  )
};