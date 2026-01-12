#!/usr/bin/python
# -*-coding:Utf-8 -*
'''
Created on 11 avr. 2017
@author: lvanni
'''

if __name__ == '__main__':
    result_in = open("total.csv", "r")
    result_out = open("final.csv", "w")
    
    for line in result_in:
        line = line.strip()
        
        result_out.write(line)
        
        args = line.split(",")
        datetime = args[0]
        identifiant = args[1]
        age = args[2]
        ordering = args[3]
        
        """
        ordering_args = ordering.split(";")
        ordering1 = ordering_args[0]
        ordering2 = ordering_args[1]
        ordering3 = ordering_args[2]
        ordering4 = ordering_args[3]
        ordering_resume = ordering_args[4]
        """
        
        reponse = args[4]
        reponse_status = args[5]
        reponse_resume = args[6]
        
        reponse_args = reponse.split(";")
        reponse_array = {}

        key_array = []

        for arg in reponse_args:
            arg_args = arg.split(":")
            if not arg_args[0] in key_array:
                reponse_array[arg_args[0]] = arg_args[2]
                key_array.append(arg_args[0])
                #print arg_args[0], arg_args[2]

        cpt = 0
        for colonne in key_array:
            result_out.write("," + colonne + "," + reponse_array[colonne])
            cpt += 1
        
        while cpt < 4:
            result_out.write(",-,-")
            cpt += 1
            
        result_out.write("\n")
            
