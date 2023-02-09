import { StatusBar } from "expo-status-bar";
import { StyleSheet, Button, Text, View, Image, ImageBackground, Dimensions } from "react-native";

import React from "react";

export default function Home({ navigation }) {
  return (
    <View style={styles.container}>
      <ImageBackground source={require("../assets/BGHome22.png")} resizeMode="repeat" style={styles.ImageBackground}>
                {/* <Text style={styles.text}>Inside</Text> */}
            </ImageBackground>
      <View style={styles.imageContainer}>
        <Image
          source={{ uri: "https://alek6.amorce.org/img/Muse_car_no.jpg" }}
          style={styles.image}
        />
      </View>
      <View buttonContainer style={styles.buttonContainer}>
        <Button
          style={styles.button}
          title="Catégories"
          onPress={() => {
            navigation.navigate("Categories");
          }}
        />
        <Button
          style={styles.button}
          title="Produits"
          onPress={() => {
            navigation.navigate("Products");
          }}
        />
      </View>
      <View style={styles.footerContainer}>
        <Text>Yo</Text>
      </View>
      <StatusBar style="auto" />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#000000",
    alignItems: "center",
    justifyContent: "center",
    color: "#000000",
  },
  imageContainer: {
    flex: 1,
    paddingTop: 58,
  },
  image: {
    width: 400,
    height: 200,
    borderRadius: 18,
    borderWidth: 1,
    borderColor: 'grey',
  },
  footerContainer: {
    flex: 1 / 3,
    alignItems: "center",
  },
  button: {
    margin: 10,
    backgroundColor: "lightblue",
    borderRadius: 10,
    color: "white",
  },
  buttonContainer: {
    padding: 10,
    margin: 10
  },
  ImageBackground: {
    flex: 1,
    width: Dimensions.get('window').width,
    height: Dimensions.get('window').height,
    position: 'absolute', 
    top: 0, 
    left: 0
},
});
