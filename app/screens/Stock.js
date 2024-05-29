import {StyleSheet, Text, TextInput, View, TouchableOpacity, Image} from 'react-native';
import { useNavigation } from '@react-navigation/core';
import React, { useState } from 'react';
import RollbackOutlined from '@ant-design/icons';
import TableItem from '../component/TableItem';

export default function AddItem() {

    const navigation = useNavigation();

    const HandleNavigate = () => {
        navigation.replace('Home');
        console.log('Home');
    };

    const funcHandleNavigate = () => {
        navigation.replace('Home');
    }



    return (
        <View>
            <TouchableOpacity onPress={funcHandleNavigate} style={styles.logout}>
            <Text style={styles.cancel}>Cancel</Text>
            </TouchableOpacity>
            <View>
                <RollbackOutlined style={styles.icon} onPress={HandleNavigate}/>
            </View>

            {/*<View style={styles.inputview}>
                <TextInput
                  placeholder="Numéro de série"
                  style={styles.input}
                />
                <TextInput
                  placeholder="Taille"
                  style={styles.input}
                />
                <TextInput
                  placeholder="Couleur"
                  style={styles.input}
                />
                 <TextInput
                  placeholder="Marque"
                  style={styles.input}
                />
                <TextInput
                  placeholder="Prix"
                  style={styles.input}
                />
                <TextInput
                  placeholder="Quantité"
                  style={styles.input}
                />
                <TextInput
                  placeholder="Nom du produit"
                  style={styles.input}
                />
            </View>     */}
            <TableItem />    
        </View>
    );
}

const styles = StyleSheet.create({
    logout: {
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
        width: 200,
        margin: 20,
      },

    dashbord: {
        fontSize: 30,
        textAlign: 'center',
        justifyContent: 'center',
    },

    icon: {
        fontSize: 40,
        marginRight: 10,
    },

    cancel: {
        flex: 1, 
        justifyContent: 'center',
        alignItems: 'center',
    },

    inputview: {
        flex: 1, 
        justifyContent: 'center',
        alignItems: 'center',
        marginTop: 30,
        marginBottom: 40,
        display: 'inline-flex',
    },

    input: 
    {
        alignItems: 'center',
        display: 'flex',
        justifyContent: 'center',
        borderRadius: 6,
        color: '#000000',
        backgroundColor: '#FFFFFF',
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 0.5 },
        shadowOpacity: 0.1,
        shadowRadius: 1,
        width: 170,
        height: 40,
        padding: 10,
        marginRight: 20,
        marginLeft: 20,
        margin: 7   ,
        fontSize: 15,
        textAlign: 'center',
      },
      
});

