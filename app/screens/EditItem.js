import { StyleSheet, Text, TextInput, View, TouchableOpacity, Image, ScrollView } from 'react-native';
import { useNavigation } from '@react-navigation/core';
import React, { useState } from 'react';
import ImagePicker from 'react-native-image-picker';
const ItemImage = './assets/SneakHubLogo.PNG';


export default function EditItem() {

  const navigation = useNavigation();
  const [image, setImage] = useState(null);

  const HandleNavigate = (path) => {
    navigation.replace(path);
  };

  const pickImage = () => {
    const options = {
      title: 'Select Image',
      storageOptions: {
        skipBackup: true,
        path: 'images',
      },
  };
    
    ImagePicker.showImagePicker(options, (response) => {
      console.log('Response:', response);
      if (response.didCancel) {
        console.log('User cancelled image picker');
      } else if (response.error) {
        console.error('ImagePicker Error:', response.error);
      } else {
        const source = { uri: response.uri };
        setImage(source);
      }
    });
  };

  return (
    <ScrollView>
    <View style={styles.container}>
      <View style={styles.header}>
        <TouchableOpacity onPress={() => HandleNavigate('Home')} style={styles.logout}>
          <Text>Cancel</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.logout}>
          <Text>Save</Text>
        </TouchableOpacity>
      </View>
      <Text style={styles.dashbord}>Edit Item</Text>
      <TouchableOpacity onPress={pickImage} style={styles.logout}>
      {image && <Image source={image} style={styles.previewImage} />}
          <Text>Choose Image</Text>
        </TouchableOpacity>
      <View>
      <TextInput
          placeholder="Brand"
          style={styles.input}
        />
        <TextInput
          placeholder="Name"
          style={styles.input}
        />
        <TextInput
          placeholder="Color"
          style={styles.input}
        />
         <TextInput
          placeholder="Size"
          style={styles.input}
        />
         <TextInput
          placeholder="Stock"
          style={styles.input}
        />
        <TextInput
          placeholder="Price"
          style={styles.input}
        />
        <TextInput
          placeholder="Total Price Entry"
          style={styles.input}
        />
        </View>
      <View style={styles.item}>
        <TouchableOpacity style={styles.logout}>
          <Text>Save Items</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.logout}>
          <Text>Delete Item</Text>
        </TouchableOpacity>
      </View>
    </View>
    </ ScrollView>
  );
}

const styles = StyleSheet.create({
  previewImage: {
    width: 100,
    height: 100,
    resizeMode: 'cover',
    borderRadius: 5,
    marginVertical: 10,
  },
  input: {
    borderRadius: 6,
    color: '#3D3D3D',
    backgroundColor: '#fff',
    borderWidth: 0,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 0.5 },
    shadowOpacity: 0.1,
    shadowRadius: 1,
    width: 300,
    padding: 10,
    marginLeft: 'auto',
    marginRight: 'auto',
    margin: 10,
  },
  container: {
    flexShrink: 1,
  },
  header: {
    color: 'black',
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  logout: {
    color: 'black',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    padding: 10,
    borderRadius: 6,
    color: '#3D3D3D',
    backgroundColor: '#fff',
    borderWidth: 0,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 0.5 },
    shadowOpacity: 0.1,
    shadowRadius: 1,
    userSelect: 'none',
    margin: 10,
    width: 200,
    marginLeft: 'auto',
    marginRight: 'auto',
  },
  dashbord: {
    fontSize: 30,
    textAlign: 'center',
    justifyContent: 'center',
  },
  item: {
    width: 250,
    height: 80,
    marginLeft: 'auto',
    marginRight: 'auto',
    padding: 10,
    margin: 10,
    marginBottom: 20,
  },
  icon: {
    fontSize: 40,
    marginRight: 10,
  },
  text: {
    fontSize: 17,
  },
});
