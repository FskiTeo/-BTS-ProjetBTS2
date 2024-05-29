import React, {useEffect, useState} from 'react';
import {View, Text, StyleSheet, TouchableOpacity} from 'react-native';
import EditItem from "../screens/EditItem";
import {useNavigation} from "@react-navigation/core";

const TableItem = () => {

    const navigation = useNavigation();

    const [tableData, setTableData] = useState();

    useEffect(() => {
        fetch('http://localhost:8080/api/articles')
            .then(response => response.json())
            .then(data => {
                setTableData(data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }, []);

    const HandleNavigate = (id) => {
        navigation.navigate(EditItem, {articleId: id});
    }

    return (
        <View>
            <View style={styles.row}>
                <Text style={styles.textT}>Id</Text>
                <Text style={styles.textT}>Name</Text>
                <Text style={styles.textT}>Price</Text>
            </View>
            {tableData && tableData.map((item, index) => (
                <TouchableOpacity onPress={() => HandleNavigate(item.id)} >
                    <View style={styles.row} key={index}>
                        <Text style={styles.text}>{item.id}</Text>
                        <Text style={styles.text}>{item.title}</Text>
                        <Text style={styles.text}>{item.selling_price}</Text>
                    </View>
                </TouchableOpacity>
            ))}
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
        alignItems: 'center',
        justifyContent: 'center',
    },
    row: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        padding: 10,
        margin: 10,
        backgroundColor: '#f8f8f8',
        borderRadius: 5,
        borderWidth: 1,
        borderColor: '#ddd',
        textAlign: 'center',
    },
    text: {
        fontSize: 12,
        color: '#333',
    },

    textT: {
        fontWeight: 'bold',
        fontSize: 14,
        color: '#333',
    },
    
});

export default TableItem;