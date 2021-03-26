<?php

namespace App\Service;



class CalculateurFrais
{
    public function Calculatrice($montant)
    {
        
        if ($montant>0 && $montant<=5000) {
            $commission = 425;
        } elseif ($montant>5000 && $montant<=10000) {
            $commission = 850;
        } elseif ($montant>10000 && $montant<=15000) {
            $commission = 1270;
        } elseif ($montant>15000 && $montant<=20000) {
            $commission = 1695;
        } elseif ($montant>20000 && $montant<=50000) {
            $commission = 2500;
        } elseif ($montant>50000 && $montant<=60000) {
            $commission = 3000;
        } elseif ($montant>60000 && $montant<=70000) {
            $commission = 4000;
        } elseif ($montant>75000 && $montant<=120000) {
            $commission = 5000;
        } elseif ($montant>120000 && $montant<=150000) {
            $commission = 6000;
        } elseif ($montant>150000 && $montant<=200000) {
            $commission = 7000;
        } elseif ($montant>200000 && $montant<=250000) {
            $commission = 8000;
        } elseif ($montant>250000 && $montant<=300000) {
            $commission = 9000;
        } elseif ($montant>300000 && $montant<=400000) {
            $commission = 12000;
        } elseif ($montant>400000 && $montant<=750000) {
            $commission = 15000;
        } elseif ($montant>750000 && $montant<=900000) {
            $commission = 22000;
        } elseif ($montant>900000 && $montant<=1000000) {
            $commission = 25000;
        } elseif ($montant>1000000 && $montant<=1125000) {
            $commission = 27000;
        } elseif ($montant>1125000 && $montant<=1400000) {
            $commission = 30000;
        } elseif ($montant>1400000 && $montant<=2000000) {
            $commission = 30000;
        } elseif ($montant>2000000) {
            $commission = $montant*0.02;
        }
    
        return $commission;
    }
}