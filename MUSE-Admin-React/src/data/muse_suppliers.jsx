import axios from "axios";

export default async function Suppliers() {
  // With function 'fetch'
  // const suppliers = (await (await fetch("https://127.0.0.1:8000/api/suppliers")).json())["hydra:member"];
  // console.log(suppliers);

  // With 'axios' ("proxy" : "http://localhost:8000" is needed to be written in 'package.json)
  const suppliers = (
    await axios.get("/api/suppliers", {
      headers: {
        "Accept": "application/json"
      }
    })).data;

  return (
    suppliers.map((item) => (
      {
        id: item?.id,
        name: item?.name,
      }
    )
    )
  )
};