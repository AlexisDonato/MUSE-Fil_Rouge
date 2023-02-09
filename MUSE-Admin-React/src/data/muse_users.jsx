import axios from "axios";

export default async function Users() {
  // With function 'fetch'
  // const users = (await (await fetch("https://127.0.0.1:8000/api/users")).json())["hydra:member"];
  // console.log(users);

  // With 'axios' ("proxy" : "http://localhost:8000" is needed to be written in 'package.json)
  const users = (
    await axios.get("/api/users", {
      headers: {
        "Accept": "application/json"
      }
    })).data;
    console.log(users);
  return (
    users.map((item) => (
      {
        id: item?.id,
        name: item?.userName,
        lastName: item?.userLastname,
        email: item?.email,
        roles: item?.roles,
        birthdate: item?.birthdate,
        phoneNumber: item?.phoneNumber,
        carts: item?.carts,
        registerDate: item?.registerDate,
        vat: item?.vat,
        pro: item?.pro,
        proCompanyName: item?.proCompanyName,
        proJobPosition: item?.proJobPosition,
        proDuns: item?.proDuns,
        address: item?.address,
        agreeTerms: item?.agreeTerms,
        verified: item?.verified,
      }
    )
    )
  )
};