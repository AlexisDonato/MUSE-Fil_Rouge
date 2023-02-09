import axios from "axios";

export default async function Categories() {
  // With function 'fetch'
  // const categories = (await (await fetch("https://127.0.0.1:8000/api/categories")).json())["hydra:member"];
  // console.log(categories);

  // With 'axios' ("proxy" : "http://localhost:8000" is needed to be written in 'package.json)
  const categories = (
    await axios.get("/api/categories", {
      headers: {
        "Accept": "application/json"
      }
    })).data;
    console.log(categories);
  return (
    categories.map((item) => (
      {
        id: item?.id,
        name: item?.name,
        parentCategory: item?.parentCategory,
        image: item?.image,
      }
    )
    )
  )
};