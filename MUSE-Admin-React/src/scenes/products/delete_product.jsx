 import axios from "axios";
 
 export const handleDelete = (id) => {
    axios.delete(`/api/products/` + id, {
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      }
    })
      .then(res => console.log(res.data))
      .catch(err => console.log(err))
  };

  export default handleDelete();