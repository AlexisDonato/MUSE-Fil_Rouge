import React from "react";
import { ScrollView, Image, ImageBackground, View, Dimensions } from "react-native";
import { Table, Row, Rows } from "react-native-table-component";

export default function ProductsByCategory({ route }) {
    const product = route.params?.product ? route.params?.product : "";
    console.log(route);

    const styles = {
        container: {
          padding: 16,
          paddingTop: 10,
          backgroundColor: "white",
          borderRadius: 10,
          alignSelf: "center",
          width: "95%",
          shadowColor: "#000",
          shadowOpacity: 0.25,
          shadowRadius: 3.84,
          elevation: 5,
          marginTop: 10,
          zIndex: 1,
        },
        head: {
          height: 10,
          backgroundColor: "#f2f2f2",
        },
        text1: {
          fontWeight: "bold",
          margin: 6,
          color: "dark",
          textBreak: "break-all",
        },
        text2: {
          margin: 6,
          color: "dark",
          textBreak: "break-all",
          fontWeight: "bold",
          borderWidth: 1,
          borderColor: "dark",
        },
        ImageBackground: {
          width: Dimensions.get("window").width,
          height: Dimensions.get("window").height,
          position: "absolute",
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          zIndex: -1,
        },
        imagesContainer: {
          flexDirection: "row",
          alignItems: "center",
          justifyContent: "space-around",
          marginTop: 30,
        },
        image: {
          width: 200,
          height: 200,
          marginHorizontal: 3,
          marginTop: 10,
          borderRadius: 5,
          borderWidth: 1,
          borderColor: "grey",
        },
        table: {
          marginTop: 6,
        },
        row: {
          borderWidth: 1,
          borderColor: "dark",
        },
        stripedRow: {
          backgroundColor: "lightgrey",
        },
      };

    const tableData = [
        ["FOURNISSEUR", product.supplier?.name],
        ["PRODUIT", product.name],
        ["PRIX", product.price + " €" + " TTC"],
        ["REDUCTION", product.discountRate > 0 ? (product.discountRate * 100 + " % = " + ((product.price * (1 - product.discountRate)).toFixed(2)) + " €") : "Non"],
        ["QUANTITE EN STOCK", product.quantity > 0 ? (product.quantity + " en stock") : "RUPTURE DE STOCK!"],
        ["DESCRIPTION", product.description],
        ["CONTENU", product.content],
    ];

    return (
        <ScrollView>
            <ImageBackground source={require("../assets/BGHome22.png")} resizeMode="repeat" style={styles.ImageBackground} />
            <View style={styles.imagesContainer}>
            {product.image && <Image source={{ uri: `https://alek6.amorce.org/img/${product.image}` }} style={styles.image} />}
            {product.image1 && <Image source={{ uri: `https://alek6.amorce.org/img/${product.image1}` }} style={styles.image} />}
            {product.image2 && <Image source={{ uri: `https://alek6.amorce.org/img/${product.image2}` }} style={styles.image} />}
            </View>
            <View style={styles.container}>
                <Table style={styles.table}>
                    <Rows
                        data={tableData}
                        object={styles.text2}
                        textStyle={{fontSize: 16}}
                        style={styles.row}
                    />
                </Table>
            </View>
        </ScrollView>
    );
};

