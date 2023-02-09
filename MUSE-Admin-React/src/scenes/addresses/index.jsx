import { Box, IconButton } from "@mui/material";
import { DataGrid, GridToolbar } from "@mui/x-data-grid";
import { tokens } from "../../theme";
import DeleteIcon from '@mui/icons-material/DeleteOutlineRounded';
import CreateIcon from '@mui/icons-material/Create';
import SaveIcon from '@mui/icons-material/Save';
import AddCircleOutlineIcon from '@mui/icons-material/AddCircleOutline';
import { useTheme } from "@mui/material";
import { useState, useEffect } from "react";


import { Link } from "react-router-dom";

import Header from "../../components/Header";

import "../../components/style.css"

import Addresses from "../../data/muse_addresses";


const AddressesIndex = () => {
  const [addresses, setAddresses] = useState([]);
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);


  useEffect(() => {
    Addresses().then(data => setAddresses(data));

  }, []);


  const columns = [
    { field: "id", headerName: "ID", flex: 0.5 },
    { 
      field: "user", 
      header: "User", 
      editable: true, 
      flex: 10,
    },
    { field: "name", headerName: "Name", editable: true },
    {
      field: "country",
      headerName: "Country",
      editable: true
    },
    {
      field: "zipcode",
      headerName: "Zipcode",
      minWidth: 150,
      editable: true,
      // headerAlign: "left",
      // align: "left",
    },
    {
      field: "city",
      headerName: "City",
      minWidth: 200,
      editable: true
    },
    {
      field: "pathNumber",
      headerName: "Path Number",
      editable: true
    },
    {
      field: "pathType",
      headerName: "Path Type",
      editable: true, 
      flex: 10
    },
    {
      field: "billingAddress",
      headerName: "Billing Address",
    },
    {
      field: "deliveryAddress",
      headerName: "Delivery Address",
    },
    {
      field: "actions",
      headerName: "",
      renderCell: () => (
        <Box>
          <IconButton title="Save" aria-label="delete">
            <SaveIcon />
          </IconButton>
          <IconButton title="Edit" aria-label="edit">
            <CreateIcon />
          </IconButton>
          <IconButton title="Delete" aria-label="new">
            <DeleteIcon />
          </IconButton>
        </Box>
      )
    },
  ];

  return (
    <Box m="20px">
      <Header title="Addresses Index" subtitle="List of all the Addresses" />
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
        <Link to="/add_new_address" style={{ textDecoration: "none" }}>
          <IconButton title="Add new" aria-label="new">
            <AddCircleOutlineIcon />Add New
          </IconButton>
        </Link>
        <DataGrid
          rows={addresses}
          columns={columns}
          components={{ Toolbar: GridToolbar }}
        />
      </Box>
    </Box>
  );
};

export default AddressesIndex;