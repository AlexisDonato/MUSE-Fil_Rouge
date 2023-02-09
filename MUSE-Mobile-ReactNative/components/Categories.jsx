import { useState, useEffect } from "react";
import { StyleSheet, Text, View, Pressable, ScrollView, Image, FlatList, ActivityIndicator, ImageBackground, Dimensions } from "react-native";

import { createNativeStackNavigator, createStackScreenNavigator } from '@react-navigation/native-stack';

import ProductsByCategory from '../components/ProductsByCategory';

import CategoriesData from "../data/muse_categories";

const Stack = createNativeStackNavigator();

export default function Categories({ route, navigation }) {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);

  console.log(categories)
  useEffect(() => {
    CategoriesData()
      .then((categories) => {
        setCategories(
          categories
         .filter(category => Array.isArray(category.product) && category.product?.length > 0)
        );
        setLoading(false);
      })
      .catch((error) => console.log(error));
  }, []);

  return (

    <ScrollView
      style={styles.container}>
      <ImageBackground source={require("../assets/BGHome22.png")} resizeMode="repeat" style={styles.ImageBackground}>
      </ImageBackground>
      {loading ? <ActivityIndicator size="large" /> : <FlatList />}
      <View style={styles.cardContainer}>
        {categories.map((category, i) => (
          <Pressable
            style={styles.card}
            key={i}
            onPress={() => {
              navigation.navigate("ProductsByCategory", {
                categoryId: category.id,
                categoryName: category.name,
                categoryProducts: category.product,
              });
            }}
          >
            <Image
              source={
                category.image
                  ? { uri: `https://alek6.amorce.org/img/${category.image}` }
                  : ""
              }
              style={styles.image}
            />
              <Text style={styles.categoryNameText}>{category.name}</Text>
          </Pressable>
        ))}
      </View>
    </ScrollView>

  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#000000",
  },
  cardContainer: {
    flexDirection: "row",
    flexWrap: "wrap",
    justifyContent: "center",
    alignItems: "center",
    marginTop: 20,
    padding: 20,
  },
  card: {
    width: Dimensions.get('window').width,
    height: 150,
    borderRadius: 8,
    marginBottom: 20,
    overflow: "hidden",
    backgroundColor: "dark",
    color: "white",
    padding: 25,
    borderWidth: 2,
    borderColor: 'grey',
  },
  image: {
    height: 200,
    width: Dimensions.get('window').width,
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
  },
  categoryNameText: {
    backgroundColor: "lightgrey",
    padding: 10,
    borderRadius: 25,
    alignSelf: "flex-end",
    zIndex: 1,
    alignItems: "center",
    justifyContent: "center",
    borderWidth: 1,
    borderColor: 'black',
    fontSize: 18.0,
    color: "#000000",
    textAlign: "center",
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
