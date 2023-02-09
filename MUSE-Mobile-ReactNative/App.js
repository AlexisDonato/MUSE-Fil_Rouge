import React from 'react';

import { useState, useEffect } from 'react';

import Home from './components/Home';
import Categories from './components/Categories';
import Products from './components/Products';
import ProductsByCategory from './components/ProductsByCategory';
import Product from './components/Product';

import Ionicons from 'react-native-vector-icons/Ionicons';

import CategoriesData from "./data/muse_categories";
import ProductsData from "./data/muse_products";

import { createNativeStackNavigator, createStackScreenNavigator } from '@react-navigation/native-stack';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';

const Tab = createBottomTabNavigator();
const Stack = createNativeStackNavigator();

export default function App() {
  const [categories, setCategories] = useState([]);
  const [products, setProducts] = useState([]);


  useEffect(() => {
    ProductsData()
      .then(data => setProducts(data))
      .catch(error => console.log(error));
  }, []);

  useEffect(() => {
    CategoriesData()
      .then(data => setCategories(data))
      .catch(error => console.log(error));
  }, []);

  return (
    <NavigationContainer>
      <Tab.Navigator
        screenOptions={({ route }) => ({
          tabBarIcon: ({ focused, color, size }) => {
            let iconName;

            if (route.name === 'Home') {
              iconName = focused ? 'ios-home' : 'ios-home-outline';
            } else if (route.name === 'Categories') {
              iconName = focused ? 'ios-list' : 'ios-list-outline';
            } else if (route.name === 'Products') {
              iconName = focused ? 'musical-notes' : 'musical-notes';
            }
            return <Ionicons name={iconName} size={size} color={color} />;
          },
          tabBarActiveTintColor: 'darkturquoise',
          tabBarInactiveTintColor: 'gray',
          tabBarShowLabel: false,
          tabBarStyle: [
            {
              display: "flex"
            },
            null
          ]

        })}
      >
        
        <Tab.Screen name="Home" component={Home} options={{ tabBarLabel: 'Accueil', }} />
        <Tab.Screen
          name="Categories"
          component={Categories}
          options={{
            tabBarBadge: categories.length,
            tabBarBadgeStyle: {
              maxWidth: 30,
              maxHeight: 10,
              fontSize: 8,
              lineHeight: 9,
              alignSelf: undefined,
              backgroundColor: 'turquoise',
            }
          }} />
        <Tab.Screen
          name="Products"
          component={Products}
          options={{
            title: "Products",
            tabBarBadge: products.length,
            tabBarBadgeStyle: {
              maxWidth: 30,
              maxHeight: 10,
              fontSize: 8,
              lineHeight: 9,
              alignSelf: undefined,
              backgroundColor: 'turquoise',
            }
          }}
        />
        <Stack.Screen
          name="ProductsByCategory"
          component={ProductsByCategory}
          options={({ route }) => ({
            title: route.params?.categoryName
          })}
        />
        <Stack.Screen
          name="Product"
          component={Product}
          options={({ route }) => ({
            headerShown: false,
            title: route.params?.product
          })}
        />
      </Tab.Navigator>
    </NavigationContainer>
  );
};
