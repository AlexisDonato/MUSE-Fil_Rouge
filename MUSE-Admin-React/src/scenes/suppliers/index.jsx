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

import Suppliers from "../../data/muse_suppliers";


const SuppliersIndex = () => {
  const [suppliers, setSuppliers] = useState([]);
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);


  useEffect(() => {
    Suppliers().then(data => setSuppliers(data));

  }, []);


  const columns = [
    { field: "id", headerName: "ID", flex: 2 },
    { field: "name", headerName: "First Name", editable: true, flex: 2 },

    {
      field: "actions",
      headerName: "",
      flex: 1,
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
      <Header title="Suppliers Index" subtitle="List of all the Suppliers" />
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
        <Link to="/add_new_supplier" style={{ textDecoration: "none" }}>
          <IconButton title="Add new" aria-label="new">
            <AddCircleOutlineIcon />Add New
          </IconButton>
        </Link>
        <DataGrid
          rows={suppliers}
          columns={columns}
          components={{ Toolbar: GridToolbar }}
        />
      </Box>
    </Box>
  );
};

export default SuppliersIndex;