import { Box, IconButton } from "@mui/material";
import { DataGrid, GridToolbar } from "@mui/x-data-grid";
import { tokens } from "../../theme";
import CreateIcon from '@mui/icons-material/Create';
import AddCircleOutlineIcon from '@mui/icons-material/AddCircleOutline';
import { useTheme } from "@mui/material";
import { useState, useEffect } from "react";

import { Link } from "react-router-dom";

import Header from "../../components/Header";

import "../../components/style.css"

import Products from "../../data/muse_products";


const ProductsIndex = () => {
  const [products, setProducts] = useState([]);
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);


  useEffect(() => {
    Products().then(data => setProducts(data));
  }, []);

  const columns = [
    { field: "id", headerName: "ID", flex: 0.8 },
    { field: "name", headerName: "Name", editable: true },
    {
      field: "supplier",
      headerName: "Supplier",
      editable: true,
      renderCell: (params) => (params.value && params.value.name) || '-'
    },
    {
      field: "category",
      headerName: "Category",
      minWidth: 150,
      editable: true,
      renderCell: (params) => (params.value && params.value.name) || '-'
      // headerAlign: "left",
      // align: "left",
    },
    {
      field: "price",
      headerName: "Price",
      editable: true
    },
    {
      field: "description",
      headerName: "Description",
      minWidth: 200,
      editable: true
    },
    {
      field: "content",
      headerName: "Content",
      minWidth: 200,
      editable: true
    },
    {
      field: "discountRate",
      headerName: "Discount Rate",
    },
    {
      field: "quantity",
      headerName: "Quantity",
    },
    {
      field: "image",
      headerName: "Image",
      editable: true
    },
    {
      field: "image1",
      headerName: "Image1",
      editable: true
    },
    {
      field: "image2",
      headerName: "Image2",
      editable: true
    },
 
    {
      field: "actions",
      headerName: "",
      renderCell: (param) =>{ 
        return(
        <Box>
          <Link to={`/edit_product/${param.row.id}`} style={{ textDecoration: "none" }}>
          <IconButton title="Edit" aria-label="edit" color="secondary">
            <CreateIcon />
          </IconButton>
          </Link>
        </Box>
      )
    }
    },
  ];

  return (
    <Box m="20px">
      <Header title="Products Index" subtitle="List of all the Products" />
      <Box height="75vh"
        m="40px 0 0 0"
        sx={{
          "& .MuiDataGrid-root": {
            border: "none",
          },
          "& .MuiDataGrid-cell": {
            borderBottom: "none",
          },
          "& .name-column--cell": {
            color: colors.greenAccent[300],
          },
          "& .MuiDataGrid-columnHeaders": {
            backgroundColor: colors.blueAccent[700],
            borderBottom: "none",
          },
          "& .MuiDataGrid-virtualScroller": {
            backgroundColor: colors.primary[400],
          },
          "& .MuiDataGrid-footerContainer": {
            borderTop: "none",
            backgroundColor: colors.blueAccent[700],
          },
          "& .MuiCheckbox-root": {
            color: `${colors.greenAccent[200]} !important`,
          },
          "& .MuiDataGrid-toolbarContainer .MuiButton-text": {
            color: `${colors.grey[100]} !important`,
          },
        }}
      >
        <Link to="/add_new_product" style={{ textDecoration: "none" }}>
          <IconButton title="Add new" aria-label="new" color="warning">
            <AddCircleOutlineIcon color="warning" style={{ margin: "10px" }} />Add New Product
          </IconButton>
        </Link>
        <DataGrid
          rows={products}
          columns={columns}
          components={{ Toolbar: GridToolbar }}
        />
      </Box>
    </Box>
  );
};

export default ProductsIndex;