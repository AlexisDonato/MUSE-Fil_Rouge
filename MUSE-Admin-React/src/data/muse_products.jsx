import axios from "axios";

export default async function Products() {
  // With function 'fetch'
  // const products = (await (await fetch("https://127.0.0.1:8000/api/products")).json())["hydra:member"];
  // console.log(products);

  // With 'axios' ("proxy" : "http://localhost:8000" is needed to be written in 'package.json)
  const products = (
    await axios.get("/api/products", {
      headers: {
        "Accept": "application/json"
      }
    })).data;

  return (
    products.map((item) => (
      {
        id: item?.id,
        name: item?.name,
        price: item?.price,
        description: item?.description,
        content: item?.content,
        discount: item?.discount,
        discountRate: item?.discountRate,
        quantity: item?.quantity,
        image: item?.image,
        image1: item?.image1,
        image2: item?.image2,
        supplier: item?.supplier,
        category: item?.category,
      }
    )
    )
  )
};