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

import Users from "../../data/muse_users";


const UsersIndex = () => {
  const [users, setUsers] = useState([]);
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);


  useEffect(() => {
    Users().then(data => setUsers(data));

  }, []);


  const columns = [
    { field: "id", headerName: "ID", flex: 0.8, },
    { field: "name", headerName: "First Name", editable: true },
    {
      field: "lastName",
      headerName: "Last Name",
      editable: true
    },
    {
      field: "email",
      headerName: "Email",
      minWidth: 150,
      editable: true,
      // headerAlign: "left",
      // align: "left",
    },
    {
      field: "roles",
      headerName: "Roles",
      minWidth: 200,
      editable: true
    },
    {
      field: "phoneNumber",
      headerName: "Phone Number",
      editable: true
    },
    {
      field: "birthdate",
      headerName: "Birthdate",
      editable: true
    },
    {
      field: "address",
      headerName: "Address",
      renderCell: (params) => (
        <ul className="flex">
          {params.value.map((address, index) => (
            <li key={index}>{address.id}</li>
          ))}
        </ul>
      ),
    },
    {
      field: "carts",
      headerName: "Carts",
      minWidth: 200,
      renderCell: (params) => (
        <ul className="flex">
          {params.value.map((cart, index) => (
            <li key={index}>{cart.clientOrderId}</li>
          ))}
        </ul>
      ),
    },
    {
      field: "registerDate",
      headerName: "Register Date",
      editable: true
    },
    {
      field: "agreeTerms",
      headerName: "Agree Terms",
      editable: true
    },
    {
      field: "verified",
      headerName: "Is Verified",
      editable: true
    },
    {
      field: "pro",
      headerName: "Pro",
    },
    {
      field: "vat",
      headerName: "VAT",
      editable: true
    },
    {
      field: "proCompanyName",
      headerName: "Pro Company Name",
      editable: true
    },
    {
      field: "proJobPosition",
      headerName: "Pro Job Posiiton",
      editable: true
    },
    {
      field: "proDuns",
      headerName: "Pro DUNS",
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
      <Header title="Users Index" subtitle="List of all the users" />
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
        <Link to="/add_new_user" style={{ textDecoration: "none" }}>
          <IconButton title="Add new" aria-label="new">
            <AddCircleOutlineIcon />Add New
          </IconButton>
        </Link>
        <DataGrid
          rows={users}
          columns={columns}
          components={{ Toolbar: GridToolbar }}
        />
      </Box>
    </Box>
  );
};

export default UsersIndex;