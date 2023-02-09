import { useState, useEffect } from 'react';
import { StyleSheet, View, Pressable, ScrollView, Image, FlatList, ActivityIndicator, ImageBackground, Dimensions } from 'react-native';
import { Avatar, Button, Card, Text } from 'react-native-paper';

import Ionicons from 'react-native-vector-icons/Ionicons';

export default function ProductsByCategory({ route, navigation }) {
    const products = route.params?.categoryProducts ? route.params?.categoryProducts : [];
console.log(products)
    return (
        <ScrollView horizontal={true}>
            <ImageBackground source={require("../assets/BGHome22.png")} resizeMode="repeat" style={styles.ImageBackground}>
            </ImageBackground>
            <View style={styles.container}>
                {products.map((product, i) => (
                    <Pressable key={i} onPress={() => {
                        navigation.navigate("Product", {
                          product: product,
                        });
                       }}>
                        <Card style={styles.card}>
                        <Card.Title title={product.supplier?.name} />
                            <Card.Content>
                                <Text variant="titleLarge">{product.name}</Text>
                                <Text variant="bodyMedium">{product.price + " €" + " TTC"}</Text>
                                <Text>{product.discountRate > 0 ? (<Text style={styles.discount}>({"- " + product.discountRate * 100 + " % = " + (product.price * (1 - product.discountRate)) + " €"})</Text>) : ""}</Text>
                            </Card.Content>
                            <Card.Cover source={product.image ? { uri: `https://127.0.0.1:8000/img/${product.image}` } : ""} />
                            <Card.Actions>
                                <Text>({product.quantity > 0 ? product.quantity + " en stock" : "RUPTURE DE STOCK!"})</Text>
                                <Button size="small" style={styles.button}>
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
        backgroundColor: 'transparent'
    },
    container: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
    },
    footerContainer: {
        flex: 1 / 3,
        alignItems: 'center',
    },
    footerText: {
        flex: 1,
        alignItems: 'center',
        padding: 10,
    },
    card: {
        margin: 10,
    },
    image: {
        height: 200,
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
    },
    content: {
        borderWidth: 1,
        borderColor: 'black',
        backgroundColor: 'grey'
    },
    actions: {
        borderWidth: 1,
        borderColor: 'black',
    },
    button: {
        borderColor: "darkturquoise",
        backgroundColor: "lightblue",
    },
    discount: {
        color: "darkorange",
    },
    ImageBackground: {
        width: Dimensions.get('window').width,
        height: Dimensions.get('window').height,
        position: "absolute",
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        zIndex: -1
    },
});
