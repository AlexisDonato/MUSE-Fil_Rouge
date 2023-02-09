import { Box, Button, TextField } from "@mui/material";
import React, { useState } from 'react';
import { useNavigate } from "react-router-dom";
import axios from "axios";
import Cookies from 'js-cookie'

const Login = () => {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");


    const navigate = useNavigate();

    const handleEmailChange = (event) => {
        setEmail(event.target.value);
    }

    const handlePasswordChange = (event) => {
        setPassword(event.target.value);
    }

    const values =
    {
        "username": email,
        "password": password
    }


    const handleSubmit = (event) => {
        event.preventDefault();
        axios.post("/api/login_check", values,
            {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }

            })
            .then(response => {
                console.log(response);

                Cookies.set('token', response.data.token)

                if (Cookies.get("token"))
                    axios.interceptors.request.use(
                        config => {
                            config.headers['Authorization'] = `Bearer ${Cookies.get("token")}`;
                            return config;
                        },
                        error => {
                            return Promise.reject(error);
                        }
                    );
                navigate("/dashboard")
            })
            .catch(error => {
                console.log(error)
            })
    }

    return (
        <form onSubmit={handleSubmit}>
            <Box>
                <div style={{ display: "flex", flexDirection: "column", alignItems: "center", margin: "10px" }}>
                    <TextField
                        label="Email"
                        type="email"
                        value={email}
                        onChange={handleEmailChange}
                        style={{ backgroundColor: "darkgrey", marginBottom: "20px", marginTop: "100px", width: "400px" }}
                        sx={{ borderRadius: '2px' }}
                    />

                    <TextField
                        label="Password"
                        type="password"
                        value={password}
                        onChange={handlePasswordChange}
                        style={{ backgroundColor: "darkgrey" }}
                        sx={{ gridColumn: "span 4", borderRadius: '2px', marginBottom: "20px", width: "400px" }}
                    />
                    <Button type="submit" title="Login" aria-label="login" style={{ backgroundColor: "darkgrey" }}>Login</Button>
                </div>
            </Box>

        </form>
    )
}

export default Login;