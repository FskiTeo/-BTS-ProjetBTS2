import React, { useState } from 'react';
import { View, Text, StyleSheet } from 'react-native';

const TableItem = () => {
    const data = [
        { name: 'Item 1', price: 10.99, stock: 10, serialNumber: '123456', color: 'Black', size: '42', brand: 'Nike'},
        { name: 'Item 2', price: 20.99, stock: 20, serialNumber: '123457', color: 'White', size: '43', brand: 'Adidas'},
    ];

    const [tableData, setTableData] = useState(data);

    return (
        <View>
            <View style={styles.row}>
                <Text style={styles.textT}>Serial Number</Text>
                <Text style={styles.textT}>Name</Text>
                <Text style={styles.textT}>Color</Text>
                <Text style={styles.textT}>Size</Text>
                <Text style={styles.textT}>Brand</Text>
                <Text style={styles.textT}>Price</Text>
                <Text style={styles.textT}>Stock</Text>
            </View>
            {tableData.map((item, index) => (
                <View key={index} style={styles.row}>
                    <Text style={styles.text}>{item.serialNumber}</Text>
                    <Text style={styles.text}>{item.name}</Text>
                    <Text style={styles.text}>{item.color}</Text>
                    <Text style={styles.text}>{item.size}</Text>
                    <Text style={styles.text}>{item.brand}</Text>
                    <Text style={styles.text}>{item.price.toFixed(2)} €</Text>
                    <Text style={styles.text}>{item.stock}</Text>
                </View>
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