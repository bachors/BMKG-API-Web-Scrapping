<?php

// Just4Fun by iBacor

class Bmkg
{
    
    function __construct()
    {
        $this->user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0';
        $this->url        = 'http://www.bmkg.go.id/BMKG_Pusat/';
        
        // include simple html dom
        require('simple_html_dom.php');
    }
    
    private function ayocurl($get)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $this->url);
        curl_setopt($ch, CURLOPT_URL, $this->url . $get);
        if (!$html = curl_exec($ch)) {
            return 'offline';
        } else {
            curl_close($ch);
            return $html;
        }
    }
    
    private function latlng($kota, $find)
    {
        $data = array(
            "Banda_Aceh" => array(
                "lat" => 5.5482904,
                "lng" => 95.3237559
            ),
            "Medan" => array(
                "lat" => 3.5951956,
                "lng" => 98.6722227
            ),
            "Pekanbaru" => array(
                "lat" => 0.5070677,
                "lng" => 101.4477793
            ),
            "Batam" => array(
                "lat" => 1.0456264,
                "lng" => 104.0304535
            ),
            "Padang" => array(
                "lat" => -0.9470832,
                "lng" => 100.417181
            ),
            "Jambi" => array(
                "lat" => -1.6101229,
                "lng" => 103.6131203
            ),
            "Palembang" => array(
                "lat" => -2.9760735,
                "lng" => 104.7754307
            ),
            "Pangkal_Pinang" => array(
                "lat" => -2.1316266,
                "lng" => 106.1169299
            ),
            "Bengkulu" => array(
                "lat" => -3.7928451,
                "lng" => 102.2607641
            ),
            "Bandar_Lampung" => array(
                "lat" => -5.3971396,
                "lng" => 105.2667887
            ),
            "Pontianak" => array(
                "lat" => -0.0263303,
                "lng" => 109.3425039
            ),
            "Samarinda" => array(
                "lat" => -0.4948232,
                "lng" => 117.1436154
            ),
            "Palangkaraya" => array(
                "lat" => -2.2161048,
                "lng" => 113.913977
            ),
            "Banjarmasin" => array(
                "lat" => -3.3186067,
                "lng" => 114.5943784
            ),
            "Manado" => array(
                "lat" => 1.4748305,
                "lng" => 124.8420794
            ),
            "Gorontalo" => array(
                "lat" => 0.5435442,
                "lng" => 123.0567693
            ),
            "Palu" => array(
                "lat" => -0.9002915,
                "lng" => 119.8779987
            ),
            "Kendari" => array(
                "lat" => -3.9984597,
                "lng" => 122.5129742
            ),
            "Makassar" => array(
                "lat" => -5.1476651,
                "lng" => 119.4327314
            ),
            "Majene" => array(
                "lat" => -3.0297251,
                "lng" => 118.9062794
            ),
            "Ternate" => array(
                "lat" => 0.7898868,
                "lng" => 127.3753792
            ),
            "Ambon" => array(
                "lat" => -3.6553932,
                "lng" => 128.1907723
            ),
            "Jayapura" => array(
                "lat" => -2.5916025,
                "lng" => 140.6689995
            ),
            "Sorong" => array(
                "lat" => -0.8819986,
                "lng" => 131.2954834
            ),
            "Biak" => array(
                "lat" => -1.0381022,
                "lng" => 135.9800848
            ),
            "Manokwari" => array(
                "lat" => -0.8614531,
                "lng" => 134.0620421
            ),
            "Merauke" => array(
                "lat" => -8.4991117,
                "lng" => 140.4049814
            ),
            "Kupang" => array(
                "lat" => -10.1771997,
                "lng" => 123.6070329
            ),
            "Sumbawa_Besar" => array(
                "lat" => -8.504043,
                "lng" => 117.428497
            ),
            "Mataram" => array(
                "lat" => -8.5769951,
                "lng" => 116.1004894
            ),
            "Denpasar" => array(
                "lat" => -8.6704582,
                "lng" => 115.2126293
            ),
            "Jakarta" => array(
                "lat" => -6.2087634,
                "lng" => 106.845599
            ),
            "Serang" => array(
                "lat" => -6.1103661,
                "lng" => 106.1639749
            ),
            "Bandung" => array(
                "lat" => -6.9174639,
                "lng" => 107.6191228
            ),
            "Semarang" => array(
                "lat" => -7.0051453,
                "lng" => 110.4381254
            ),
            "Yogyakarta" => array(
                "lat" => -7.7955798,
                "lng" => 110.3694896
            ),
            "Surabaya" => array(
                "lat" => -7.2574719,
                "lng" => 112.7520883
            )
        );
        
        $kota2 = str_replace(" ", "_", $kota);
        return $data[$kota2][$find];
    }
    
    function cuaca()
    {
        $data = $this->ayocurl('Informasi_Cuaca/Prakiraan_Cuaca/Prakiraan_Cuaca_Indonesia.bmkg');
        
        $result = array();
        
        if ($data == "offline") {
            $result['status']  = "error";
			$result['message'] = "offline";
        } else {
            $result['status'] = "success";
            $result['view']   = "cuaca";
            $html             = str_get_html($data);
            $table            = $html->find('table[class=table-hover]', 0);
            
            $sekarang = explode("Ini", $table->find('th', 1)->innertext);
            $besok    = explode("Hari", $table->find('th', 2)->innertext);
            
            foreach ($table->find('tr') as $i=>$tr) {
                if ($i != 0) {
                    
					$kota = $tr->find('td', 0)->innertext;
                    
                    $cuaca_sekarang             = explode("Suhu : ", $tr->find('td', 1)->innertext);
                    $suhu_sekarang              = explode("Kelembaban : ", $cuaca_sekarang[1]);
                    $suhu_sekarang_minmax       = explode(" - ", $suhu_sekarang[0]);
                    $kelembaban_sekarang        = $suhu_sekarang[1];
                    $kelembaban_sekarang_minmax = explode(" - ", $kelembaban_sekarang);
                    
                    $cuaca_besok             = explode("Suhu : ", $tr->find('td', 2)->innertext);
                    $suhu_besok              = explode("Kelembaban : ", $cuaca_besok[1]);
                    $suhu_besok_minmax       = explode(" - ", $suhu_besok[0]);
                    $kelembaban_besok        = $suhu_besok[1];
                    $kelembaban_besok_minmax = explode(" - ", $kelembaban_besok);
                    
                    $cells            = array(
                        'kota' => strip_tags($kota),
                        'maps' => array(
                            'latitude' => strip_tags($this->latlng($kota, 'lat')),
                            'longitude' => strip_tags($this->latlng($kota, 'lng'))
                        ),
                        'prakiraan' => array(
                            'sekarang' => array(
                                'tgl' => strip_tags($sekarang[1]),
                                'cuaca' => strip_tags($cuaca_sekarang[0]),
                                'suhu' => array(
                                    'min' => strip_tags($suhu_sekarang_minmax[0]),
                                    'max' => '' . strip_tags(intval($suhu_sekarang_minmax[1])) . ''
                                ),
                                'kelembaban' => array(
                                    'min' => strip_tags($kelembaban_sekarang_minmax[0]),
                                    'max' => strip_tags(str_replace(" %", "", $kelembaban_sekarang_minmax[1]))
                                )
                            ),
                            'besok' => array(
                                'tgl' => strip_tags($besok[1]),
                                'cuaca' => strip_tags($cuaca_besok[0]),
                                'suhu' => array(
                                    'min' => strip_tags($suhu_besok_minmax[0]),
                                    'max' => '' . strip_tags(intval($suhu_besok_minmax[1])) . ''
                                ),
                                'kelembaban' => array(
                                    'min' => strip_tags($kelembaban_besok_minmax[0]),
                                    'max' => strip_tags(str_replace(" %", "", $kelembaban_besok_minmax[1]))
                                )
                            )
                        )
                    );
                    $result['data'][] = $cells;
                }
            }
        }
        
        return $result;
    }
    
    function gempa()
    {
        $data = $this->ayocurl('Gempabumi_-_Tsunami/Gempabumi/Gempabumi_Dirasakan.bmkg');
        
        $result = array();
        
        if ($data == "offline") {
            $result['status']  = "error";
			$result['message'] = "offline";
        } else {
            $result['status'] = "success";
            $result['view']   = "gempa";
            $html             = str_get_html($data);
            $table            = $html->find('table[class=table-hover]', 0);
            
            $i = 0;
            foreach ($table->find('tr') as $tr) {
                if ($i != 0) {
                    
                    $tgl       = $tr->find('td', 1)->innertext;
                    $waktu     = $tr->find('td', 2)->innertext;
                    $lintang   = $tr->find('td', 3)->innertext;
                    $magnitudo = $tr->find('td', 4)->innertext;
                    $kedalaman = $tr->find('td', 5)->innertext;
                    $ex        = explode('<br>', $tr->find('td', 6)->innertext);
                    $lokasi    = $ex[0];
                    $dirasakan = $tr->find('span[class=label-warning]', 0)->innertext;
                    $img       = $tr->find('img', 0)->src;
                    
                    $cells            = array(
                        'tgl' => strip_tags($tgl),
                        'waktu' => strip_tags($waktu),
                        'lintang_bujur' => strip_tags($lintang),
                        'magnitudo' => strip_tags($magnitudo),
                        'kedalaman' => strip_tags($kedalaman),
                        'lokasi' => strip_tags($lokasi),
                        'dirasakan' => strip_tags($dirasakan),
                        'img' => strip_tags($img)
                    );
                    $result['data'][] = $cells;
                }
                $i++;
            }
        }
        
        return $result;
    }
    
}

?>
