import { useState } from "react";
import { Routes, Route, useNavigate } from "react-router-dom";
import Topbar from "./scenes/global/Topbar";
import Sidebar from "./scenes/global/Sidebar";

import Dashboard from "./scenes/dashboard";
import Login from "./scenes/login";

import Users from "./scenes/users";
import Addresses from "./scenes/addresses";
import Suppliers from "./scenes/suppliers";
import Categories from "./scenes/categories";

import Products from "./scenes/products";
import AddNewProduct from "./scenes/products/add_new_product";
import EditProduct from "./scenes/products/edit_product";

import OrderDetails from "./scenes/orderDetails";
import Carts from "./scenes/carts";
import Coupons from "./scenes/coupons";

import Bar from "./scenes/bar";
import Form from "./scenes/form";
import Line from "./scenes/line";
import Pie from "./scenes/pie";
import FAQ from "./scenes/faq";
import Geography from "./scenes/geography";
import { CssBaseline, ThemeProvider } from "@mui/material";
import { ColorModeContext, useMode } from "./theme";
import Calendar from "./scenes/calendar/calendar";

import Test from "./Test";
import axios from "axios";

function App() {
  const [theme, colorMode] = useMode();
  const [isSidebar, setIsSidebar] = useState(true);
  const navigate = useNavigate();

  axios.interceptors.response.use(response => {
    return response
  },
  error => {
    if(error.response.data.code === 401 || error.response.data.code === 403 ) {
      // document.ElementsByTagName('body').innerHTML=error.response.data.message;
      navigate("/");
    }
  })
  

  return (
    <ColorModeContext.Provider value={colorMode}>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        <div className="app">
          <Sidebar isSidebar={isSidebar} />
          <main className="content">
            <Topbar setIsSidebar={setIsSidebar} />
            <Routes>

              <Route path="/" element={<Login />} />

              <Route path="/dashboard" element={<Dashboard />} />

              <Route path="/test" element={<Test />} />

              <Route path="/users" element={<Users />} />
              <Route path="/addresses" element={<Addresses />} />
              <Route path="/suppliers" element={<Suppliers />} />
              <Route path="/categories" element={<Categories />} />

              <Route path="/products" element={<Products />} />
              <Route path="/add_new_product" element={<AddNewProduct />} />
              <Route path="/edit_product/:id" element={<EditProduct />} />

              <Route path="/orderDetails" element={<OrderDetails />} />
              <Route path="/carts" element={<Carts />} />
              <Route path="/coupons" element={<Coupons />} />

              <Route path="/form" element={<Form />} />
              <Route path="/bar" element={<Bar />} />
              <Route path="/pie" element={<Pie />} />
              <Route path="/line" element={<Line />} />
              <Route path="/faq" element={<FAQ />} />
              <Route path="/calendar" element={<Calendar />} />
              <Route path="/geography" element={<Geography />} />
            </Routes>
          </main>
        </div>
      </ThemeProvider>
    </ColorModeContext.Provider>
  );
}

export default App;
