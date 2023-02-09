import { useState, useEffect } from "react";
import { StyleSheet, View, Pressable, ScrollView, ImageBackground, Dimensions } from "react-native";
import { Button, Card, Text } from "react-native-paper";

import Ionicons from "react-native-vector-icons/Ionicons";

import ProductsData from "../data/muse_products";

export default function Products({ route, navigation }) {
    const [products, setProducts] = useState([]);

    useEffect(() => {
        ProductsData()
            .then((products) => {
                setProducts(products);
            })
            .catch(error => console.log(error));
    }, []);

    return (
        <ScrollView horizontal={true} showsHorizontalScrollIndicator={true} style={styles.scrollView}>
            <ImageBackground source={require("../assets/BGHome22.png")} resizeMode="repeat" style={styles.ImageBackground}>
            </ImageBackground>
            <View style={styles.container}>
                {products
                    .map((product, i) => (
                        <Pressable key={i} onPress={() => {
                            navigation.navigate("Product", {
                                product: product,
                            });
                        }}>
                            <Card style={styles.card}>
                                <Card.Title title={product.supplier?.name} />
                                <Card.Content>
                                    <Text
                                        variant="titleLarge"
                                    >{product.name}</Text>
                                    <Text
                                        variant="bodyMedium"
                                    >{(product.price).toFixed(2) + " €" + " TTC"}</Text>
                                    {product.discountRate > 0 ? (<Text variant="bodySmall" style={styles.discount}>({"- " + (product.discountRate) * 100 + " % = " + ((product.price * (1 - product.discountRate)).toFixed(2)) + " €"})</Text>) : <Text></Text>}
                                </Card.Content>
                                <Card.Cover source={product.image ? { uri: `https://127.0.0.1:8000/img/${product.image}` } : ""} />
                                <Card.Actions style={styles.content}>
                                    <Text>({product.quantity > 0 ? product.quantity + " en stock" : "RUPTURE DE STOCK!"})</Text>
                                    <Button style={styles.button}>
                                        <Ionicons name="eye-outline" size="large" color="darkturquoise" />
                                    </Button>
                                </Card.Actions>
                            </Card>
                        </Pressable>
                    ))}

            </View>
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    scrollView: {
        backgroundColor: "transparent"
    },
    container: {
        flexDirection: "row",
        alignItems: "center",
        justifyContent: "center",
    },
    footerContainer: {
        flex: 1 / 3,
        alignItems: "center",
    },
    footerText: {
        flex: 1,
        alignItems: "center",
        padding: 10,
    },
    card: {
        margin: 10,
    },
    image: {
        height: 200,
        position: "absolute",
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
    },
    actions: {
        borderWidth: 1,
        borderColor: "black",
    },
    button: {
        borderColor: "darkturquoise",
        backgroundColor: "lightblue",
    },
    discount: {
        color: "darkorange",
        fontSize: 12,
    },
    ImageBackground: {
        width: Dimensions.get("window").width,
        height: Dimensions.get("window").height,
        position: "absolute",
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        zIndex: -1
    },
});