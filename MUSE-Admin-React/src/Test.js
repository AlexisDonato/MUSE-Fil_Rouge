import { useEffect } from "react";

import axios from "axios";

function Test() {

  useEffect(()=>{
    axios.get("/api/users", {
      headers: {
        "Accept": "application/json"
      }
    }).then((response)=>{
      console.log(response.data)
    })
  }, [])

  return (
      <div>
        {/* {users} */}
      </div>
    );
}

export default Test;
