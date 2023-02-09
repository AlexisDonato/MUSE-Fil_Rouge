import { Box, Button, TextField, Select } from "@mui/material";
import { MenuItem } from "@material-ui/core";

import { useParams, useNavigate } from 'react-router-dom';

import * as yup from "yup";

import useMediaQuery from "@mui/material/useMediaQuery";
import Header from "../../components/Header";

import axios from "axios";

import { useState, useEffect } from "react";

const EditProduct = () => {
  const isNonMobile = useMediaQuery("(min-width:600px)");

  const { id } = useParams();

  const [name, setName] = useState("");
  const [supplier, setSupplier] = useState("");
  const [category, setCategory] = useState("");
  const [discountRate, setDiscountRate] = useState("");
  const [price, setPrice] = useState("");
  const [quantity, setQuantity] = useState("");
  const [description, setDescription] = useState("");
  const [content, setContent] = useState("");
  const [image, setImage] = useState();
  const [image1, setImage1] = useState();
  const [image2, setImage2] = useState();
  const [fileName, setFileName] = useState("");
  const [fileName1, setFileName1] = useState("");
  const [fileName2, setFileName2] = useState("");

  const [supplierOptions, setSupplierOptions] = useState([]);
  const [categoryOptions, setCategoryOptions] = useState([]);

  const navigate = useNavigate();

  // HANDLEFORMSUBMIT 
  const handleFormSubmit = (event) => {
    event.preventDefault();

    // VALUES TO PASS IN REQUEST
    const values =
    {
      name: name,
      price: parseInt(price),
      description: description,
      content: content,
      discountRate: discountRate,
      quantity: parseInt(quantity),
      supplier: supplier,
      category: category,
    }

    console.log(values);

    try {
      // validationSchema.validate(values, { abortEarly: false });

      const response = axios.put("/api/products/" + id, values, {
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
      });

      // IMAGES UPLOAD
      if (image) {
        const formData = new FormData();
        formData.append("image", image);
        axios.post("/api/upload/" + id + "/0", formData, {
          headers: { "Content-Type": "multipart/form-data" }
        });
      }
      if (image1) {
        const formData1 = new FormData();
        formData1.append("image", image1);
        axios.post("/api/upload/" + id + "/1", formData1, {
          headers: { "Content-Type": "multipart/form-data" }
        });
      }
      if (image2) {
        const formData2 = new FormData();
        formData2.append("image", image2);
        axios.post("/api/upload/" + id + "/2", formData2, {
          headers: { "Content-Type": "multipart/form-data" }
        });
      }
      navigate("/products");
    } catch (error) {
      console.log(error);
    }
  };

  // HANDLEINPUT
  const handleInput = (event, setState) => {
    console.log("----------------------------------------");
    console.log(event);
    console.log(event.target);
    console.log("----------------------------------------");
    if (event.target.value != null) { setState(event.target.value) };
  }

  // HANDLEFILES ON IMAGES
  const handleFile = (event, setState) => {
    const file = event.currentTarget.files[0];
    setState(file);
    setFileName(file.name);
  }
  const handleFile1 = (event, setState, setFileName1) => {
    const file1 = event.currentTarget.files[0];
    setState(file1);
    setFileName1(file1.name);
  }
  const handleFile2 = (event, setState, setFileName2) => {
    const file2 = event.currentTarget.files[0];
    setState(file2);
    setFileName2(file2.name);
  }

  // SETSTATES ON CONST
  useEffect(() => {
    axios.get("/api/products/" + id).then((response) => {
      setName(response.data.name);
      setSupplier(response.data.supplier['@id']);
      setCategory(response.data.category['@id']);
      setDiscountRate(response.data.discountRate);
      setPrice(response.data.price);
      setQuantity(response.data.quantity);
      setDescription(response.data.description);
      setContent(response.data.content);
      // console.log(response.data);
    })

    // GET SUPPLIERS
    axios
      .get("/api/suppliers", {
        headers: {
          "Accept": "application/json"
        }
      })
      .then((response) => {
        setSupplierOptions(response.data)
      });

    // GET CATEGORIES
    axios.get("/api/categories", {
      headers: {
        "Accept": "application/json"
      }
    })
      .then(response => {
        setCategoryOptions(response.data)
      });
  }, []);

  const handleDelete = (id) => {
    axios.delete(`/api/products/` + id, {
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      }
    })
      .then(res => {
        console.log(res.data)
        navigate("/products")
      })
      .catch(err => console.log(err))
  };

  return (
    <Box m="20px">
      <Header title="EDIT PRODUCT" subtitle="Edit Product" />

      <form onSubmit={handleFormSubmit} encType="multipart/form-data">
        <Box
          display="grid"
          gap="30px"
          gridTemplateColumns="repeat(4, minmax(0, 1fr))"
          sx={{
            "& > div": { gridColumn: isNonMobile ? undefined : "span 4" },
          }}
        >
          <TextField
            fullWidth
            variant="filled"
            type="text"
            label="name"
            onChange={(event) => handleInput(event, setName)}
            value={name}
            name="name"
            style={{ backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 2", bg: 'grey', borderRadius: '2px' }}
          />

          <TextField
            name="supplier"
            value={supplier}
            select
            helperText="Supplier"
            label="Supplier"
            placeholder={supplier.name}
            style={{ borderRadius: '3px', backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 1" }}
            onChange={(event) => handleInput(event, setSupplier)}>
            {supplierOptions.map((supplierOption) => (
              <MenuItem
                value={"/api/suppliers/" + supplierOption.id}
              >
                {supplierOption.name}
              </MenuItem>
            ))}
          </TextField>
          <TextField
            name="category"
            value={category}
            select
            helperText="Category"
            label="Cayegory"
            placeholder={category.name}
            style={{ borderRadius: '3px', backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 1" }}
            onChange={(event) => handleInput(event, setCategory)}>
            {categoryOptions.map((categoryOption) => (
              <MenuItem
                key={categoryOption.id}
                selected={categoryOption.name === category.name}
                value={"/api/categories/" + categoryOption.id}
              >
                {categoryOption.name}
              </MenuItem>
            ))}
          </TextField>

          <TextField
            fullWidth
            variant="filled"
            type="text"
            label="Discount Rate"
            onChange={(event) => { handleInput(event, setDiscountRate) }}
            value={discountRate}
            name="discountRate"
            style={{ backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 1", bg: 'grey', borderRadius: '2px' }}
          />
          <TextField
            fullWidth
            variant="filled"
            type="text"
            label="Price"
            onChange={(event) => { handleInput(event, setPrice) }}
            value={price}
            name="price"
            style={{ backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 1", bg: 'grey', borderRadius: '2px' }}
          />
          <TextField
            fullWidth
            variant="filled"
            type="text"
            label="Quantity"
            onChange={(event) => { handleInput(event, setQuantity) }}
            value={quantity}
            name="quantity"
            style={{ backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 1", bg: 'grey', borderRadius: '2px' }}
          />
          <TextField
            fullWidth
            variant="filled"
            type="text"
            label="Description"
            onChange={(event) => { handleInput(event, setDescription) }}
            value={description}
            name="description"
            style={{ backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 4", bg: 'grey', borderRadius: '2px' }}
          />
          <TextField
            fullWidth
            variant="filled"
            type="text"
            label="Content"
            onChange={(event) => { handleInput(event, setContent) }}
            value={content}
            name="content"
            style={{ backgroundColor: '#333333' }}
            sx={{ gridColumn: "span 4", bg: 'grey', borderRadius: '2px', color: 'white' }}
          />
          <div style={{ display: "flex", flexDirection: "column", alignItems: "center", margin: "10px" }}>
            <Button
              variant="contained"
              component="label"
              style={{ border: fileName ? "1px solid green" : "1px solid grey", color: fileName ? 'lightgreen' : 'white' }}
            >
              {fileName ? fileName : "Image upload"}
              <input
                type="file"
                hidden
                accept="image/*"
                onChange={(event) => { handleFile(event, setImage, setFileName) }}
              />
            </Button>
            {image && <img alt="" title={fileName} src={
              typeof URL.createObjectURL === 'function'
                ? URL.createObjectURL(new Blob([image], { type: image }))
                : `https://localhost:8000/public/img/${fileName}.jpg`
            }
              style={{ width: '200px', height: '200px', objectFit: 'cover', display: "inline-block", margin: "10px" }} />}
          </div>

          <div style={{ display: "flex", flexDirection: "column", alignItems: "center", margin: "10px" }}>
            <Button
              variant="contained"
              component="label"
              style={{ border: fileName1 ? "1px solid green" : "1px solid grey", color: fileName1 ? 'lightgreen' : 'white' }}
            >
              {fileName1 ? fileName1 : "Image 1 upload"}
              <input
                type="file"
                hidden
                accept="image/*"
                onChange={(event) => { handleFile1(event, setImage1, setFileName1) }}
              />
            </Button>
            {image1 && <img alt="" title={fileName1} src={
              typeof URL.createObjectURL === 'function'
                ? URL.createObjectURL(new Blob([image1], { type: image1 }))
                : `https://localhost:8000/public/img/${fileName1}.jpg`
            } 
              style={{ width: '200px', height: '200px', objectFit: 'cover', display: "inline-block", margin: "10px" }} />}
          </div>
          <div style={{ display: "flex", flexDirection: "column", alignItems: "center", margin: "10px" }}>
            <Button
              variant="contained"
              component="label"
              style={{ border: fileName2 ? "1px solid green" : "1px solid grey", color: fileName2 ? 'lightgreen' : 'white' }}
            >
              {fileName2 ? fileName2 : "Image 2 upload"}
              <input
                type="file"
                hidden
                accept="image/*"
                onChange={(event) => { handleFile2(event, setImage2, setFileName2) }}
              />
            </Button>
            {image2 && <img alt="" title={fileName2} src={
              typeof URL.createObjectURL === 'function'
                ? URL.createObjectURL(new Blob([image2], { type: image2 }))
                : `https://localhost:8000/public/img/${fileName2}`
            }
              style={{ width: '200px', height: '200px', objectFit: 'cover', display: "inline-block", margin: "10px" }} />}
          </div>
        </Box>
      </form>
      <div style={{ position: "relative" }}>
        <Box display="flex" justifyContent="end" mt="20px">
          <Button type="submit" color="secondary" variant="contained" onClick={handleFormSubmit}>
            Edit Product
          </Button>
        </Box>
        <Box display="flex" justifyContent="end" mt="20px">
          <Button
            onClick={() => window.confirm('Are you sure you wish to delete this item?') ? handleDelete(id) : navigate(0)}
            color="error" variant="contained"
          >
            Delete Product
          </Button>
        </Box>
        <Box display="flex" justifyContent="end" mt="20px">
          <Button
            onClick={() => navigate(-1)}
            color="secondary" variant="contained">
            Back
          </Button>
        </Box>
      </div>
    </Box>
  );
};

const phoneRegExp =
  /^((\+[1-9]{1,4}[ -]?)|(\([0-9]{2,3}\)[ -]?)|([0-9]{2,4})[ -]?)*?[0-9]{3,4}[ -]?[0-9]{3,4}$/;
const nameRegExp = /^[A-Za-z]$/

const validationSchema = yup.object().shape({
  name: yup.string().matches(nameRegExp, "Name is not valid").required("Name required"),
  supplier: yup.string().required("Required"),
  category: yup.string().required("La catégorie est requise"),
  price: yup.string().required("Required"),
  description: yup
    .string()
    // .matches(phoneRegExp, "Phone number is not valid")
    .required("Required"),
  content: yup.string().required("Required"),
  quantity: yup.string().required("Required"),
  discountRate: yup
  .number()
  .required("Le taux de remise est requis")
  .positive("Le taux de remise doit être positif"),
price: yup
  .number()
  .required("Le prix est requis")
  .positive("Le prix doit être positif"),
quantity: yup
  .number()
  .required("La quantité est requise")
  .positive("La quantité doit être positive"),
  // image: yup.mixed().required("Required"),
  // image1: yup.mixed().required("Required"),
  // image2: yup.mixed().required("Required"),
});

export default EditProduct;
