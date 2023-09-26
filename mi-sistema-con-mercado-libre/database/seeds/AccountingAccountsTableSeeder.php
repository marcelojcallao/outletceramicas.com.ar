<?php

use Illuminate\Database\Seeder;

class AccountingAccountsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accounting_accounts')->delete();
        
        \DB::table('accounting_accounts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'account' => 1000000,
                'imputable' => 'N',
                'name' => 'ACTIVO',
                'parent_account' => 0,
                'created_at' => '2022-01-10 09:49:04',
                'updated_at' => '2022-01-10 09:49:05',
                'description' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'account' => 2000000,
                'imputable' => 'N',
                'name' => 'PASIVO',
                'parent_account' => 0,
                'created_at' => '2022-01-10 09:49:34',
                'updated_at' => NULL,
                'description' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'account' => 3000000,
                'imputable' => 'N',
                'name' => 'PATRIMONIO NETO',
                'parent_account' => 0,
                'created_at' => '2022-01-10 09:50:47',
                'updated_at' => '2022-01-10 09:50:48',
                'description' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'account' => 4000000,
                'imputable' => 'N',
                'name' => 'RESULTADO POSITIVO',
                'parent_account' => 0,
                'created_at' => '2022-01-10 09:51:18',
                'updated_at' => '2022-01-10 09:51:19',
                'description' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'account' => 5000000,
                'imputable' => 'N',
                'name' => 'RESULTADO NEGATIVO',
                'parent_account' => 0,
                'created_at' => '2022-01-10 09:51:39',
                'updated_at' => NULL,
                'description' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'account' => 1001000,
                'imputable' => 'N',
                'name' => 'CAJA',
                'parent_account' => 1,
                'created_at' => '2022-01-10 10:10:27',
                'updated_at' => NULL,
                'description' => 'Dinero en efectivo',
            ),
            6 => 
            array (
                'id' => 7,
                'account' => 1002000,
                'imputable' => 'N',
                'name' => 'FONDO FIJO',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Pequeña cantidad de dinero para usar en gastos menores',
            ),
            7 => 
            array (
                'id' => 8,
                'account' => 1003000,
                'imputable' => 'N',
                'name' => 'BANCO XXX C/C',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Dinero depositado en una cuenta corriente bancaria',
            ),
            8 => 
            array (
                'id' => 9,
                'account' => 1004000,
                'imputable' => 'N',
                'name' => 'BANCO XXX C/A',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Dinero depositado en una caja de ahorros bancaria	',
            ),
            9 => 
            array (
                'id' => 10,
                'account' => 1005000,
                'imputable' => 'N',
                'name' => 'VALORES A DEPOSITAR',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Cheques al día recibidos de terceros',
            ),
            10 => 
            array (
                'id' => 11,
                'account' => 1006000,
                'imputable' => 'N',
                'name' => 'VALORES DIFERIDOS A DEPOSITAR',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Cheques de pago diferido recibidos  de terceros	',
            ),
            11 => 
            array (
                'id' => 12,
                'account' => 1007000,
                'imputable' => 'N',
                'name' => 'CHEQUES RECHAZADOS',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Cheques rechazados	',
            ),
            12 => 
            array (
                'id' => 13,
                'account' => 1008000,
                'imputable' => 'N',
                'name' => 'MONEDA EXTRANJERA',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Dinero en moneda extranjera',
            ),
            13 => 
            array (
                'id' => 14,
                'account' => 1008100,
                'imputable' => 'N',
                'name' => 'DOLARES',
                'parent_account' => 13,
                'created_at' => '2022-01-10 10:42:52',
                'updated_at' => '2022-01-10 10:42:52',
                'description' => 'DOLARES',
            ),
            14 => 
            array (
                'id' => 15,
                'account' => 1008200,
                'imputable' => 'N',
                'name' => 'REALES',
                'parent_account' => 13,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'REALES',
            ),
            15 => 
            array (
                'id' => 16,
                'account' => 1009000,
                'imputable' => 'N',
                'name' => 'VENTAS',
                'parent_account' => 1,
                'created_at' => '2022-01-10 10:46:53',
                'updated_at' => '2022-01-10 10:46:55',
                'description' => 'Bienes destinados para la venta	',
            ),
            16 => 
            array (
                'id' => 17,
                'account' => 1010000,
                'imputable' => 'N',
                'name' => 'MUEBLES Y ÚTILES',
                'parent_account' => 1,
                'created_at' => '2022-01-10 10:48:04',
                'updated_at' => '2022-01-10 10:48:05',
                'description' => 'Sillas, escritorios, teléfonos, fotocopiadoras, etc.	',
            ),
            17 => 
            array (
                'id' => 18,
                'account' => 1011000,
                'imputable' => 'N',
                'name' => 'INSTALACIONES',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            'description' => 'Aire acondicionado, ventiladores de techos, estufas, etc. (bienes que necesitan instalarse y después no se mueven)',
            ),
            18 => 
            array (
                'id' => 19,
                'account' => 1012000,
                'imputable' => 'N',
                'name' => 'INMUEBLES',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Locales, oficinas, depósitos, galpones, etc.	',
            ),
            19 => 
            array (
                'id' => 20,
                'account' => 1013000,
                'imputable' => 'N',
                'name' => 'RODADOS',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Autos, camiones, camionetas, motos, etc.	',
            ),
            20 => 
            array (
                'id' => 21,
                'account' => 1014000,
                'imputable' => 'N',
                'name' => 'MAQUINARIAS',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Maquinas envasadoras, etiquetadoras, tornos, etc.',
            ),
            21 => 
            array (
                'id' => 22,
                'account' => 1015000,
                'imputable' => 'N',
                'name' => 'EQUIPOS DE COMPUTACIÓN',
                'parent_account' => 1,
                'created_at' => '2022-01-10 10:52:44',
                'updated_at' => '2022-01-10 10:52:44',
                'description' => 'Notebooks, pcs, impresoras, scanners, etc.	',
            ),
            22 => 
            array (
                'id' => 23,
                'account' => 1016000,
                'imputable' => 'N',
                'name' => 'DEUDORES',
                'parent_account' => 1,
                'created_at' => '2022-01-10 10:54:29',
                'updated_at' => '2022-01-10 10:54:29',
                'description' => 'DEUDORES',
            ),
            23 => 
            array (
                'id' => 24,
                'account' => 1017000,
                'imputable' => 'N',
                'name' => 'DEUDORES POR VENTAS',
                'parent_account' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Deudas a cobrar por venta de mercaderías	',
            ),
            24 => 
            array (
                'id' => 25,
                'account' => 1018000,
                'imputable' => 'N',
                'name' => 'DEUDORES VARIOS',
                'parent_account' => 23,
                'created_at' => '2022-01-10 10:55:31',
                'updated_at' => '2022-01-10 10:55:32',
            'description' => 'Deudas a cobrar por ventas de otros bienes (no mercaderías)	',
            ),
            25 => 
            array (
                'id' => 26,
                'account' => 1019000,
                'imputable' => 'N',
                'name' => 'DEUDORES MOROSOS',
                'parent_account' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Deudas vencidas no cobradas	',
            ),
            26 => 
            array (
                'id' => 27,
                'account' => 1020000,
                'imputable' => 'N',
                'name' => 'DEUDORES EN GESTIÓN JUDICIAL',
                'parent_account' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Deudas pasadas a juicio para cobrar	',
            ),
            27 => 
            array (
                'id' => 28,
                'account' => 1021000,
                'imputable' => 'N',
                'name' => 'DOCUMENTOS A COBRAR',
                'parent_account' => 23,
                'created_at' => NULL,
                'updated_at' => NULL,
            'description' => 'Deudas a cobrar documentadas (pagaré)	',
            ),
            28 => 
            array (
                'id' => 29,
                'account' => 1022000,
                'imputable' => 'N',
                'name' => 'IVA CRÉDITO FISCAL',
                'parent_account' => 1,
                'created_at' => '2022-01-10 10:58:14',
                'updated_at' => '2022-01-10 10:58:15',
                'description' => 'IVA CRÉDITO FISCAL',
            ),
            29 => 
            array (
                'id' => 30,
                'account' => 1022100,
                'imputable' => 'N',
                'name' => 'IVA CRÉDITO FISCAL 21%',
                'parent_account' => 29,
                'created_at' => '2022-01-10 10:59:00',
                'updated_at' => '2022-01-10 10:59:00',
                'description' => 'IVA CRÉDITO FISCAL 21%',
            ),
            30 => 
            array (
                'id' => 31,
                'account' => 1022200,
                'imputable' => 'N',
                'name' => 'IVA CRÉDITO FISCAL 10,5%',
                'parent_account' => 29,
                'created_at' => '2022-01-10 10:59:31',
                'updated_at' => '2022-01-10 10:59:31',
                'description' => 'IVA CRÉDITO FISCAL 10,5%',
            ),
            31 => 
            array (
                'id' => 32,
                'account' => 1023000,
                'imputable' => 'N',
                'name' => 'IVA A FAVOR',
                'parent_account' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Saldo a favor del IVA luego de la liquidación	',
            ),
            32 => 
            array (
                'id' => 33,
                'account' => 2001000,
                'imputable' => 'N',
                'name' => 'DOCUMENTOS A PAGAR',
                'parent_account' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            'description' => 'Pagarés (Deuda documentada) emitidos o terceros	',
            ),
            33 => 
            array (
                'id' => 34,
                'account' => 2002000,
                'imputable' => 'N',
                'name' => 'PROVEEDORES',
                'parent_account' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            'description' => '	Deudas en cuenta corriente comercial (fiado) por compra de mercadería',
            ),
            34 => 
            array (
                'id' => 35,
                'account' => 2003000,
                'imputable' => 'N',
                'name' => 'ACREEDORES VARIOS',
                'parent_account' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            'description' => 'Deudas en cuenta corriente comercial (fiado) otros –no mercadería-',
            ),
            35 => 
            array (
                'id' => 36,
                'account' => 2004000,
                'imputable' => 'N',
                'name' => 'CHEQUES A PAGAR  ',
                'parent_account' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Cheques de pago diferido de nuestra firma	',
            ),
            36 => 
            array (
                'id' => 37,
                'account' => 2005000,
                'imputable' => 'N',
                'name' => 'IVA DÉBITO FISCAL',
                'parent_account' => 2,
                'created_at' => '2022-01-10 11:08:07',
                'updated_at' => '2022-01-10 11:08:07',
                'description' => 'IVA de las ventas realizadas	',
            ),
            37 => 
            array (
                'id' => 38,
                'account' => 2006000,
                'imputable' => 'N',
                'name' => 'IVA A PAGAR',
                'parent_account' => 2,
                'created_at' => '2022-01-10 11:08:53',
                'updated_at' => '2022-01-10 11:08:54',
                'description' => 'Saldo a pagar de la posición del IVA	',
            ),
            38 => 
            array (
                'id' => 39,
                'account' => 3001000,
                'imputable' => 'N',
                'name' => 'CAPITAL ',
                'parent_account' => 3,
                'created_at' => '2022-01-10 11:10:13',
                'updated_at' => '2022-01-10 11:10:13',
                'description' => 'Aporte inicial de los socios	',
            ),
            39 => 
            array (
                'id' => 40,
                'account' => 3002000,
                'imputable' => 'N',
                'name' => 'RESULTADO DEL EJERCICIO',
                'parent_account' => 3,
                'created_at' => '2022-01-10 11:10:42',
                'updated_at' => '2022-01-10 11:10:42',
            'description' => 'Resultados del ejercicio (ganancia o pérdida)	',
            ),
            40 => 
            array (
                'id' => 41,
                'account' => 3003000,
                'imputable' => 'N',
                'name' => 'RESERVA LEGAL',
                'parent_account' => 3,
                'created_at' => '2022-01-10 11:11:15',
                'updated_at' => '2022-01-10 11:11:15',
                'description' => 'Reserva legal	',
            ),
            41 => 
            array (
                'id' => 42,
                'account' => 3004000,
                'imputable' => 'N',
                'name' => 'RESERVA FACULTATIVA',
                'parent_account' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Reserva facultativa	',
            ),
            42 => 
            array (
                'id' => 43,
                'account' => 3005000,
                'imputable' => 'N',
                'name' => 'RESERVA ESTATUTARIA',
                'parent_account' => 3,
                'created_at' => '2022-01-10 11:12:20',
                'updated_at' => '2022-01-10 11:12:20',
                'description' => 'Reserva estatutaria	',
            ),
            43 => 
            array (
                'id' => 44,
                'account' => 4001000,
                'imputable' => 'N',
                'name' => 'VENTAS',
                'parent_account' => 4,
                'created_at' => '2022-01-10 11:14:56',
                'updated_at' => '2022-01-10 11:14:56',
                'description' => 'Ventas realizadas	',
            ),
            44 => 
            array (
                'id' => 45,
                'account' => 4002000,
                'imputable' => 'N',
                'name' => 'COMISIONES COBRADAS',
                'parent_account' => 4,
                'created_at' => '2022-01-10 11:15:55',
                'updated_at' => '2022-01-10 11:15:56',
                'description' => 'Cobro de comisiones	',
            ),
            45 => 
            array (
                'id' => 46,
                'account' => 4003000,
                'imputable' => 'N',
                'name' => 'ALQUILERES COBRADOS',
                'parent_account' => 4,
                'created_at' => '2022-01-10 11:16:27',
                'updated_at' => '2022-01-10 11:16:27',
                'description' => 'Cobro de alquiler	',
            ),
            46 => 
            array (
                'id' => 47,
                'account' => 4004000,
                'imputable' => 'N',
                'name' => 'INTERESES COBRADOS',
                'parent_account' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
                'description' => 'Cobro de intereses	',
            ),
            47 => 
            array (
                'id' => 48,
                'account' => 4005000,
                'imputable' => 'N',
                'name' => 'DESCUENTOS OBTENIDOS',
                'parent_account' => 4,
                'created_at' => '2022-01-10 11:18:35',
                'updated_at' => '2022-01-10 11:18:35',
                'description' => 'Descuentos que nos hicieron por compras',
            ),
            48 => 
            array (
                'id' => 49,
                'account' => 4006000,
                'imputable' => 'N',
                'name' => 'DIFERENICIA POSITIVA DE CAMBIO',
                'parent_account' => 4,
                'created_at' => '2022-01-10 11:19:13',
                'updated_at' => '2022-01-10 11:19:14',
                'description' => 'Diferencia positiva en el tipo de cambio	',
            ),
            49 => 
            array (
                'id' => 50,
                'account' => 4007000,
                'imputable' => 'N',
                'name' => 'SOBRANTE DE CAJA',
                'parent_account' => 4,
                'created_at' => '2022-01-10 11:19:41',
                'updated_at' => '2022-01-10 11:19:41',
                'description' => 'Sobrante de dinero en el arqueo de caja',
            ),
            50 => 
            array (
                'id' => 51,
                'account' => 4008000,
                'imputable' => 'N',
                'name' => 'SOBRANTE DE MERCADERÍAS',
                'parent_account' => 4,
                'created_at' => '2022-01-10 11:20:10',
                'updated_at' => '2022-01-10 11:20:10',
                'description' => 'Sobrante de mercaderías luego de un inventario	',
            ),
            51 => 
            array (
                'id' => 52,
                'account' => 5001000,
                'imputable' => 'N',
            'name' => 'COSTO DE MERCADERIAS VENDIDAS (CMV)',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:21:15',
                'updated_at' => '2022-01-10 11:21:15',
                'description' => 'El precio que nos costó comprar las mercaderías que vendimos',
            ),
            52 => 
            array (
                'id' => 53,
                'account' => 5002000,
                'imputable' => 'N',
                'name' => 'GASTOS GENERALES',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:21:37',
                'updated_at' => '2022-01-10 11:21:38',
            'description' => 'Gastos chicos varios (arts. de limpieza, gastos de librería, etc.)',
            ),
            53 => 
            array (
                'id' => 54,
                'account' => 5003000,
                'imputable' => 'N',
                'name' => 'SERVICIOS PAGADOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:22:05',
                'updated_at' => '2022-01-10 11:22:07',
                'description' => 'Servicios de Luz, gas, teléfono, internet, etc.	',
            ),
            54 => 
            array (
                'id' => 55,
                'account' => 5004000,
                'imputable' => 'N',
                'name' => 'IMPUESTOS PAGADOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:22:40',
                'updated_at' => '2022-01-10 11:22:40',
                'description' => 'ABL, Rentas, impuestos a favor del Estado	',
            ),
            55 => 
            array (
                'id' => 56,
                'account' => 5005000,
                'imputable' => 'N',
                'name' => 'FLETES Y ACARREOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:23:01',
                'updated_at' => '2022-01-10 11:23:01',
                'description' => 'Gastos por fletes o transportes de materias primas o mercaderías',
            ),
            56 => 
            array (
                'id' => 57,
                'account' => 5006000,
                'imputable' => 'N',
                'name' => 'SEGUROS PAGADOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:23:26',
                'updated_at' => '2022-01-10 11:23:26',
                'description' => 'Seguros de todo tipo	',
            ),
            57 => 
            array (
                'id' => 58,
                'account' => 5007000,
                'imputable' => 'N',
                'name' => 'SUELDOS Y JORNALES',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:23:50',
                'updated_at' => '2022-01-10 11:23:50',
                'description' => 'Sueldos del personal	',
            ),
            58 => 
            array (
                'id' => 59,
                'account' => 5008000,
                'imputable' => 'N',
                'name' => 'ALQUILERES PAGADOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:24:17',
                'updated_at' => '2022-01-10 11:24:18',
                'description' => 'Pago del alquiler	',
            ),
            59 => 
            array (
                'id' => 60,
                'account' => 5009000,
                'imputable' => 'N',
                'name' => 'INTERESES PAGADOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:24:40',
                'updated_at' => '2022-01-10 11:24:40',
                'description' => 'Pago de intereses',
            ),
            60 => 
            array (
                'id' => 61,
                'account' => 5010000,
                'imputable' => 'N',
                'name' => 'COMISIONES PAGADAS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:25:19',
                'updated_at' => '2022-01-10 11:25:20',
                'description' => 'Pago de comisiones	',
            ),
            61 => 
            array (
                'id' => 62,
                'account' => 5011000,
                'imputable' => 'N',
                'name' => 'DESCUENTOS CEDIDOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:25:50',
                'updated_at' => '2022-01-10 11:25:51',
                'description' => 'Descuentos que hicimos por ventas	',
            ),
            62 => 
            array (
                'id' => 63,
                'account' => 5012000,
                'imputable' => 'N',
                'name' => 'BONIFICACIONES CEDIDAS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:26:13',
                'updated_at' => '2022-01-10 11:26:14',
                'description' => 'Bonificaciones que hicimos en ventas	',
            ),
            63 => 
            array (
                'id' => 64,
                'account' => 5013000,
                'imputable' => 'N',
                'name' => 'GASTOS ',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:26:38',
                'updated_at' => '2022-01-10 11:26:38',
            'description' => 'Gastos grandes de todo tipo (identificados)	',
            ),
            64 => 
            array (
                'id' => 65,
                'account' => 5014000,
                'imputable' => 'N',
                'name' => 'AMORTIZACIONES',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:27:01',
                'updated_at' => '2022-01-10 11:27:01',
                'description' => 'Amortizaciones de los bienes de uso	',
            ),
            65 => 
            array (
                'id' => 66,
                'account' => 5015000,
                'imputable' => 'N',
                'name' => 'VIATICOS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:27:25',
                'updated_at' => '2022-01-10 11:27:25',
                'description' => 'Gastos por viajes de trabajo	',
            ),
            66 => 
            array (
                'id' => 67,
                'account' => 5016000,
                'imputable' => 'N',
                'name' => 'DIFERENICIA NEGATIVA DE CAMBIO',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:27:52',
                'updated_at' => '2022-01-10 11:27:53',
                'description' => 'Diferencia negativa en el tipo de cambio	',
            ),
            67 => 
            array (
                'id' => 68,
                'account' => 5017000,
                'imputable' => 'N',
                'name' => 'FALTANTE DE CAJA',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:28:17',
                'updated_at' => '2022-01-10 11:28:18',
                'description' => 'Faltante de dinero en el arqueo de caja	',
            ),
            68 => 
            array (
                'id' => 69,
                'account' => 5018000,
                'imputable' => 'N',
                'name' => 'FALTANTE DE MERCADERÍAS',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:28:41',
                'updated_at' => '2022-01-10 11:28:41',
                'description' => 'Faltante de mercaderías luego de un arqueo	',
            ),
            69 => 
            array (
                'id' => 70,
                'account' => 5019000,
                'imputable' => 'N',
                'name' => 'DEUDORES INCOBRABLES',
                'parent_account' => 5,
                'created_at' => '2022-01-10 11:29:06',
                'updated_at' => '2022-01-10 11:29:06',
                'description' => 'Deudas que ya se sabe que no se van a cobrar	',
            ),
        ));
        
        
    }
}