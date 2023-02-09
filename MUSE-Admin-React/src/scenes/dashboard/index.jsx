import { Box, Button, IconButton, Typography, useTheme } from "@mui/material";
import { tokens } from "../../theme";
import Moment from 'moment';

import DownloadOutlinedIcon from "@mui/icons-material/DownloadOutlined";
import QueryStatsIcon from '@mui/icons-material/QueryStats';
import PointOfSaleIcon from "@mui/icons-material/PointOfSale";
import EmojiPeopleIcon from '@mui/icons-material/EmojiPeople';
import PrecisionManufacturingIcon from '@mui/icons-material/PrecisionManufacturing';
import Header from "../../components/Header";
import LineChart from "../../components/LineChart";
import GeographyChart from "../../components/GeographyChart";
import BarChart from "../../components/BarChart";
import StatBox from "../../components/StatBox";
import ProgressCircle from "../../components/ProgressCircle";

import Calculations from "../../data/muse_calculations"
import Carts from "../../data/muse_carts";

import { useEffect, useState } from "react";

const Dashboard = () => {
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);

  const [todaySales, setTodaySales] = useState(0)
  const [sumTotal, setSumTotal] = useState(0);
  const [sumVal, setSumVal] = useState(0);
  const [pro, setPro] = useState(0);
  const [sumPro, setSumPro] = useState(0);
  const [sumClient, setSumClient] = useState(0);
  const [todate, setTodate] = useState(new Date());
  const [sumQuant, setSumQuant] = useState(0);
  const [sumProd, setSumProd] = useState(0);
  const [sumShip, setSumShip] = useState(0);
  const [users, setUsers] = useState(0);

  const [carts, setCarts] = useState([]);

  console.log(carts);

  let fr = new Intl.NumberFormat("fr-FR", { style: "currency", currency: "EUR" });

  useEffect(() => {
    Calculations()
      .then(val => {
        setTodaySales(val.todaySales)
        setSumTotal(val.sumTotal.toFixed(2))
        setSumVal(val.sumVal)
        setPro(val.pro)
        setSumPro(val.sumPro.toFixed(2))
        setSumClient(val.sumClient.toFixed(2))
        setTodate(val.todate)
        setSumQuant(val.sumQuant)
        setSumProd(val.sumProd)
        setSumShip(val.sumShip)
        setUsers(val.users)
      })
  }, [])

  useEffect(() => {
    Carts().then(data => setCarts(data));

  }, []);

  return (
    <Box m="20px">

      {/* HEADER */}
      <Box display="flex" justifyContent="space-between" alignItems="center">
        <Header title="DASHBOARD" subtitle="Welcome to your dashboard" />

        <Box>
          <Button
            sx={{
              backgroundColor: colors.blueAccent[700],
              color: colors.grey[100],
              fontSize: "14px",
              fontWeight: "bold",
              padding: "10px 20px",
            }}
          >
            <DownloadOutlinedIcon sx={{ mr: "10px" }} />
            Download Reports
          </Button>
        </Box>
      </Box>

      {/* GRID & CHARTS */}
      <Box
        display="grid"
        gridTemplateColumns="repeat(12, 1fr)"
        gridAutoRows="140px"
        gap="20px"
      >

        {/* ROW 1 */}
        <Box
          gridColumn="span 3"
          backgroundColor={colors.primary[400]}
          display="flex"
          alignItems="center"
          justifyContent="center"
        >
          <StatBox
            title={fr.format(todaySales)}
            subtitle="Today sales"
            progress={todaySales / sumTotal}
            increase={"+ " + todaySales / sumTotal + " %"}
            icon={
              <QueryStatsIcon
                sx={{ color: colors.greenAccent[600], fontSize: "26px" }}
              />
            }
          />
        </Box>
        <Box
          gridColumn="span 3"
          backgroundColor={colors.primary[400]}
          display="flex"
          alignItems="center"
          justifyContent="center"
        >
          <StatBox
            title={fr.format(sumTotal)}
            subtitle="Global sales"
            progress={todaySales / sumTotal}
            increase={"+ " + todaySales / sumTotal + " %"}
            icon={
              <PointOfSaleIcon
                sx={{ color: colors.greenAccent[600], fontSize: "26px" }}
              />
            }
          />
        </Box>
        <Box
          gridColumn="span 3"
          backgroundColor={colors.primary[400]}
          display="flex"
          alignItems="center"
          justifyContent="center"
        >
          <StatBox
            title={fr.format(sumClient)}
            subtitle="Clients Turnover"
            progress={sumClient / sumTotal}
            increase={((sumClient / sumTotal) * 100).toFixed(2) + " %"}
            icon={
              <EmojiPeopleIcon
                sx={{ color: colors.greenAccent[600], fontSize: "26px" }}
              />
            }
          />
        </Box>
        <Box
          gridColumn="span 3"
          backgroundColor={colors.primary[400]}
          display="flex"
          alignItems="center"
          justifyContent="center"
        >
          <StatBox
            title={fr.format(sumPro)}
            subtitle="Professionals Turnover"
            progress={sumPro / sumTotal}
            increase={((sumPro / sumTotal) * 100).toFixed(2) + " %"}
            icon={
              <PrecisionManufacturingIcon
                sx={{ color: colors.greenAccent[600], fontSize: "26px" }}
              />
            }
          />
        </Box>

        {/* ROW 2 */}
        <Box
          gridColumn="span 8"
          gridRow="span 2"
          backgroundColor={colors.primary[400]}
        >
          <Box
            mt="25px"
            p="0 30px"
            display="flex"
            justifyContent="space-between"
            alignItems="center"
          >
            <Box>
              <Typography
                variant="h5"
                fontWeight="600"
                color={colors.grey[100]}
              >
                Revenue Generated
              </Typography>
              <Typography
                variant="h3"
                fontWeight="bold"
                color={colors.greenAccent[500]}
              >
                {fr.format(sumTotal)}
              </Typography>
            </Box>
            <Box>
              <IconButton>
                <DownloadOutlinedIcon
                  sx={{ fontSize: "26px", color: colors.redAccent[600] }}
                />
              </IconButton>
            </Box>
          </Box>
          <Box height="250px" m="-20px 0 0 0">
            <LineChart isDashboard={true} />
          </Box>
        </Box>
        <Box
          gridColumn="span 4"
          gridRow="span 2"
          backgroundColor={colors.primary[400]}
          overflow="auto"
        >
          <Box
            display="flex"
            justifyContent="space-between"
            alignItems="center"
            borderBottom={`4px solid ${colors.primary[500]}`}
            colors={colors.grey[100]}
            p="15px"
          >
            <Typography color={colors.grey[100]} variant="h4" fontWeight="600">
              Recent Transactions
            </Typography>
            <div>
            <Typography color={colors.greenAccent[500]} variant="h5" fontWeight="600">
              (PRO)
            </Typography>
            <Typography color={colors.blueAccent[500]} variant="h5" fontWeight="600">
             (CLIENT)
            </Typography>
            </div>
            <Box>
              <IconButton>
                <DownloadOutlinedIcon
                  sx={{ fontSize: "26px", color: colors.redAccent[600] }}
                />
              </IconButton>
            </Box>
          </Box>

          {carts.map((cart, i) => {
            if (!cart.validated) return;
            return (
              <Box
                key={`${cart.id}-${i}`}
                display="flex"
                justifyContent="space-between"
                alignItems="center"
                borderBottom={`4px solid ${colors.primary[500]}`}
                p="15px"
              >
                <Box>
                  <Typography
                    color={cart.user.pro ? colors.greenAccent[500] : colors.blueAccent[500]}
                    variant="h5"
                    fontWeight="600"
                  >
                    {cart.user.email}
                  </Typography>
                  <Typography color={colors.grey[100]}>
                  {cart.clientOrderId}
                  </Typography>
                </Box>
                <Box color={colors.grey[100]}>{Moment(cart.orderDate).format('DD-MM-YYYY')}</Box>
                <Box
                  backgroundColor={cart.user.pro ? colors.greenAccent[500] : colors.blueAccent[500]}
                  p="5px 10px"
                  borderRadius="4px"
                >
                  {fr.format(cart.total ? cart.total : "-")}
                </Box>
              </Box>
            )
          })}
        </Box>

        {/* ROW 3 */}
        <Box
          gridColumn="span 4"
          gridRow="span 2"
          backgroundColor={colors.primary[400]}
          p="30px"
        >
          <Typography variant="h5" fontWeight="600">
            Turnovers per customer type
          </Typography>
          <Box align="right">
            <IconButton>
            </IconButton>
          </Box>
          <Box
            display="flex"
            flexDirection="column"
            alignItems="center"
            mt="25px"
          >
            <ProgressCircle size="125" progress={sumPro / sumTotal} />
            <Typography
              variant="h5"
              color={colors.greenAccent[500]}
              sx={{ mt: "15px" }}
            >
              {fr.format(sumPro) + " (PRO)"}
            </Typography>
            <Typography color={colors.blueAccent[500]}>{fr.format(sumClient) + " (CLIENTS)"}</Typography>
          </Box>
        </Box>
        <Box
          gridColumn="span 4"
          gridRow="span 2"
          backgroundColor={colors.primary[400]}
        >
          <Typography
            variant="h5"
            fontWeight="600"
            sx={{ padding: "30px 30px 0 30px" }}
          >
            Sales Quantity
          </Typography>
          <Box align="right">
            <IconButton>
            </IconButton>
          </Box>
          <Box height="250px" mt="-20px">
            <BarChart isDashboard={true} />
          </Box>
        </Box>
        <Box
          gridColumn="span 4"
          gridRow="span 2"
          backgroundColor={colors.primary[400]}
          padding="30px"
        >
          <Typography
            variant="h5"
            fontWeight="600"
            sx={{ marginBottom: "15px" }}
          >
            Geography Based Traffic
          </Typography>
          <Box height="200px">
            <GeographyChart isDashboard={true} />
          </Box>
        </Box>
      </Box>
    </Box>
  );
};

export default Dashboard;
