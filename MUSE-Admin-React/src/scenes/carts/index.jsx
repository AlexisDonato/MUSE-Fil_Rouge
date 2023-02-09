import { Box, IconButton } from "@mui/material";
import { DataGrid, GridToolbar } from "@mui/x-data-grid";
import { tokens } from "../../theme";
import DeleteIcon from '@mui/icons-material/DeleteOutlineRounded';
import CreateIcon from '@mui/icons-material/Create';
import SaveIcon from '@mui/icons-material/Save';
import AddCircleOutlineIcon from '@mui/icons-material/AddCircleOutline';
import { useTheme } from "@mui/material";
import { useState, useEffect } from "react";

import Moment from 'moment';

import { Link } from "react-router-dom";

import Header from "../../components/Header";

import "../../components/style.css"

import Carts from "../../data/muse_carts";


const CartsIndex = () => {
  const [carts, setCarts] = useState([]);
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);


  useEffect(() => {
    Carts().then(data => setCarts(data));

  }, []);


  const columns = [
    { field: "id", headerName: "ID", flex: 0.8, },
    { field: "clientOrderId", headerName: "Client Order Id", editable: true },
    {
      field: "user",
      headerName: "User",
      minWidth: 200,
      editable: true,
      renderCell: (params) => (params.value && params.value.email) || '-'
    },
    {
      field: "validated",
      headerName: "Validated ?",
      editable: true
    },
    {
      field: "orderDetails",
      headerName: "Order Details",
      minWidth: 150,
      editable: true,
      renderCell: (params) => (
        <ul className="flex">
          {params.value.map((orderDetails, index) => (
            <li key={index}>{orderDetails.id}</li>
          ))}
        </ul>
      ),
    },
    {
      field: "orderDate",
      headerName: "OrderDate",
      editable: true,
      renderCell: (params) => (
        params.value ? Moment(params.value).format('DD-MM-YYYY') : "-"
      )
    },
    {
      field: "total",
      headerName: "Total",
      editable: true
    },
    {
      field: "shipped",
      headerName: "Shipped ?",
    },
    {
      field: "shipmentDate",
      headerName: "Shipment Date",
      renderCell: (params) => (
        params.value ? Moment(params.value).format('DD-MM-YYYY') : "-"
      )
    },
    {
      field: "billingAddress",
      headerName: "Billing Address",
      editable: true,
      renderCell: (params) => (params.value && params.value.id) || '-'
    },
    {
      field: "deliveryAddress",
      headerName: "Delivery Address",
      editable: true,
      renderCell: (params) => (params.value && params.value.id) || '-'
    },
    {
      field: "additionalDiscountRate",
      headerName: "Additional Discount Rate",
      editable: true
    },
    {
      field: "invoice",
      headerName: "Invoice",
    },
    {
      field: "coupon",
      headerName: "Coupon",
      editable: true,
      renderCell: (params) => (params.value && params.value.code) || '-'
    },
    {
      field: "carrier",
      headerName: "Carrier",
      editable: true
    },
    {
      field: "carrierShipmentId",
      headerName: "Carrier Shipment Id",
      editable: true
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
      <Header title="Carts Index" subtitle="List of all the Carts" />
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
        <Link to="/add_new_cart" style={{ textDecoration: "none" }}>
          <IconButton title="Add new" aria-label="new">
            <AddCircleOutlineIcon />Add New
          </IconButton>
        </Link>
        <DataGrid
          rows={carts}
          columns={columns}
          components={{ Toolbar: GridToolbar }}
        />
      </Box>
    </Box>
  );
};

export default CartsIndex;