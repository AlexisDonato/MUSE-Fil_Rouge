import axios from "axios";

const Calculations = () => {
    return new Promise((resolve, reject) => {
        let data = {
            sumTotal: 0,
            sumVal: 0,
            pro: 0,
            sumPro: 0,
            sumClient: 0,
            todaySales: 0,
            toDate: new Date(),
            sumQuant: 0,
            sumProd: 0,
            sumShip: 0,
            users: 0
        }

        let promises = [];

        promises.push(axios.get("/api/carts", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        }).then((response) => {
            const carts = response.data;
            console.log(carts);
            let tempTodaySales = 0;
            carts.forEach((cart) => {
                if (cart.validated === true) {
                    data.sumVal += 1;
                    data.sumShip += 1;
                    data.sumTotal += Number(cart.total);
                    if (new Date(cart.orderDate).toLocaleDateString() === data.toDate.toLocaleDateString()) {
                        tempTodaySales += cart.total;
                    }
                }
                data.todaySales += tempTodaySales;
                if (cart.user.pro === true) {
                    data.pro += 1;
                    if (cart.validated === true) {
                        data.sumPro += Number(cart.total);
                    }
                } else {
                    if (cart.validated === true) {
                        data.sumClient += Number(cart.total);
                    }
                }
            });
        }));

        promises.push(axios.get("/api/products", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        }).then((response) => {
            const products = response.data;
            products.forEach(product => {
                data.sumQuant += product.quantity? product.quantity : 0
            });
        }));

        promises.push(axios.get("/api/order_details", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        }).then((response) => {
            const orderDetails = response.data;
            orderDetails.forEach((detail) => {
                data.sumProd += detail.quantity;
            });
        }));

        promises.push(axios.get("/api/users", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            }
        }).then((response) => {
            const users = response.data;
                data.users = users.length;
        }));


        Promise.all(promises).then(() => {
            resolve(data)
        })
    })

};
export default Calculations;