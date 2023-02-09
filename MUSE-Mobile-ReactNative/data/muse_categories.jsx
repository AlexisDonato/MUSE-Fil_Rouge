import axios from "axios";

// Bypasses the SSL certificate query
process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";

import Proxy from "../components/proxy.json"

export default async function CategoriesData() {
  const categories = (
    await axios.get(`https://alek6.amorce.org/api/categories`, {
      headers: {
        "Accept": "application/json",
      }
    })).data;
    console.log(categories)
  return (
    categories.map((item) => (
      {
        id: item?.id,
        name: item?.name,
        parentCategory: item?.parentCategory,
        image: item?.image,
        product: item?.product
      }
    )
    )
  )
};