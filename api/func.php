<?php
defined("BASEPATH") OR exit('No direct script access allowed!');
error_reporting(0);


/*
.---------------------------------------------------------------------------.
|    Script: Package Tracking                                               |
|   Version: 2.0.8                                                          |
|   Release: December 23, 2019 (17:46 WIB)                                  |
|    Update: April 13, 2020 (18:31 WIB)                                     |
|                                                                           |
|                     Pasal 57 ayat (1) UU 28 Tahun 2014                    |
|      Copyright © 2019, Afdhalul Ichsan Yourdan. All Rights Reserved.      |
| ------------------------------------------------------------------------- |
| Hubungi Saya:                                                             |
| - Facebook    - Afdhalul Ichsan Yourdan   - https://s.id/ShennFacebook    |
| - Instagram   - ShennBoku                 - https://s.id/ShennInstagram   |
| - Telegram    - ShennBoku                 - https://t.me/ShennBoku        |
| - Twitter     - ShennBoku                 - https://s.id/ShennTwitter     |
| - WhatsApp    - 0878 7954 2355            - 0822 1158 2471                |
'---------------------------------------------------------------------------'
*/

class PackageTracking
{
    function fdate($lang,$ymd_format) {
        $ymdhis = explode(' ',$ymd_format);
        $time = !$ymdhis[1] ? '' : ' '.$ymdhis[1];
        if($lang == 'id') {
            $month = [1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            $exp = explode('-', $ymdhis[0]);
            return $exp[2].' '.$month[(int)$exp[1]].' '.$exp[0].$time;
        } else if($lang == 'en') {
            $month = [1 =>   'January','February','March','April','May','June','July','August','September','October','November','December'];
            $exp = explode('-', $ymdhis[0]);
            return $month[(int)$exp[1]].' '.$exp[2].', '.$exp[0].$time;
        } else {
            return '';
        }
    }

    function format($data,$url = 'no') {
        $ban = ["\n","\r\n"," ","\u00a0"];
        if($url == 'no') {
            return preg_replace('/\s+/',' ',str_replace($ban ,' ',$data));
        } else {
            return preg_replace('/\s+/',' ',str_replace($ban ,' ',file_get_contents($url)));
        }
    }

    public function JnT($bill) { // JO0034489689
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $this->useProxy = 'true';
            $try = $this->connect('http://jk.jet.co.id:22234/jandt-app-ifd-web/router.do',[
                'method' => 'order.massOrderTrack',
                'data' => '{"parameter": "{\"billCodes\": \"'.$bill.'\",\"lang\": \"en\"}"}',
            ]);

            if($try['success'] == true) {
                $data = json_decode($try['data'], true)['bills'][0];
                if(isset($data['statuscode']) && count($data['details']) > 0) {
                    for($i = 1; $i <= count($data['details'])-1; $i++) {
                        $detail = $data['details'][$i];

                        $status = $detail['scanstatus'];
                        if($detail['scanscode'] == '1') $status = 'Picked Up by '.$detail['deliveryName'];
                        if($detail['scanscode'] == '2') $status = 'Departed to '.$detail['nextsite'];
                        if($detail['scanscode'] == '3') $status = 'Arrived';
                        if($detail['scanscode'] == '4') $status = 'On Delivery by '.$detail['deliveryName'];
                        if($detail['scanscode'] == '5') $status = 'Delivered to '.$detail['signer'];
                        if($detail['scanscode'] == '7') $status = 'On Hold, '.$detail['remark'];

                        $track[] = [
                            'date' => substr($detail['acceptTime'], 0, 19),
                            'desc' => $detail['state'].', '.$detail['city'],
                            'status' => $status
                        ];

                        if($detail['signer'] !== '') $receive[] = ['name' => $detail['signer'],'date' => substr($detail['acceptTime'],0,19)];
                    }

                    return ['result' => true,'data' => [
                        'courier' => 'J&T Express',
                        'waybill' => $bill,
                        'shipped' => $track[count($track)-1]['date'],
                        'received' => isset($receive) ? $receive[0] : '',
                        'tracking' => $track
                    ],'message' => 'Successfully tracked package.'];
                } else {
                    return ['result' => false,'data' => null,'message' => 'Invalid billcode.'];
                }
            } else {
                $error = isset($try['desc']) ? $try['desc'] : 'System Error';
                return ['result' => false,'data' => null,'message' => $error.'.'];
            }
        }
    }

    public function SiCepat($bill) { // 000215808615
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $ch = $this->connect('http://sicepat.com/checkAwb/doSearch',['awb[]' => $bill],'original');
            $main = explode('</tbody>',explode('<tbody>',preg_replace('/\s+/',' ',str_replace("\n",'',explode('</div></div>',explode('<div class="table-responsive">',$ch)[1])[0])))[1])[0];
            $CMonth = ['JAN' => '01','FEB' => '02','MAR' => '03','APR' => '04','MEI' => '05','JUN' => '06','JUL' => '07','AGU' => '08','AGS' => '08','SEP' => '09','OKT' => '10','NOV' => '11','DES' => '12'];

            if(!$main) {
                return ['result' => false,'data' => null,'message' => 'No result found for '.$bill.'.'];
            } else {
                $resitem = explode('<td>',str_replace(' class="hidden-xs"','',explode('</tr>',explode('<tr class="res-item">',$main)[1])[0]));
                $resdetail = explode('<table class="table ws-table">',explode('<div class="detail-title">TRACKING DETAILS</div>',$main)[1]);

                $sdate = date('Y-m-d', strtotime(strtr(strtoupper(explode(' ',explode('</td>',explode('<td>',explode('</tr>',explode('<tr>',$resdetail[1])[2])[0])[1])[0])[0]),$CMonth)));
                $rdate = date('Y-m-d', strtotime(strtr(strtoupper(explode('<div',explode('</td>',$resitem[6])[0])[0]),$CMonth)));

                $tracking = explode('<tr>',$resdetail[3]);
                for($i = 2; $i <= count($tracking)-1; $i++) {
                    $date = explode(' ',explode('</td>',explode("<td>",$tracking[$i])[1])[0]);
                    $track[] = [
                        'date' => date('Y-m-d', strtotime($date[0])).' '.$date[1],
                        'desc' => explode('</td>',explode("<td>",$tracking[$i])[2])[0]
                    ];
                }

                return ['result' => true,'data' => [
                    'courier' => 'SiCepat',
                    'waybill' => $bill,
                    'service' => str_replace('<br>',' ',explode('</td>',$resitem[3])[0]),
                    'shipped' => [
                        'name' => explode('</td>',explode('<td>',explode('</tr>',explode('<tr>',$resdetail[2])[2])[0])[1])[0],
                        'addr' => explode('</td>',explode('<td>',explode('</tr>',explode('<tr>',$resdetail[2])[3])[0])[1])[0],
                        'date' => $sdate
                    ],
                    'received' => [
                        'name' => explode('</td>',explode('<td>',explode('</tr>',explode('<tr>',$resdetail[2])[2])[0])[2])[0],
                        'recipient' => str_replace(']','',explode('[',explode('</td>',$resitem[7])[0])[1]),
                        'addr' => explode('</td>',explode('<td>',explode('</tr>',explode('<tr>',$resdetail[2])[3])[0])[2])[0],
                        'date' => $rdate
                    ],
                    'tracking' => $track,
                    'status' => explode('</td>',$resitem[8])[0]
                ],'message' => 'Successfully tracked package.'];
            }
        }
    }

    public function TIKI($bill) { // 030083620135
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $ch = $this->connect('https://www.tiki.id/id/tracking',['no_resi' => $bill],'original');
            $main = explode('</div> </div></section>',preg_replace('/\s+/',' ',str_replace(["\r\n"," "],'',explode('<div class="cnnoAll" style="margin-bottom: 5px">',$ch)[1])))[0];
            $data1 = explode(' </div> </div>',explode("<div class=\"panel-cr cnnoHeader\" data-btnseq=\"cnno_$bill\"> ",$main)[1])[0];
            $data2 = explode("<div class=\"cnnoContent\" id=\"cnno_$bill\" style=\"margin-bottom: 10px; display: block\">",$main)[1];
            $detail = explode('<td>',str_replace(['</tr> <tr> ',' width="50%"','<br>'],'',explode(' </tr> </table>',explode('<table width="100%"> <tr> ',$data2)[1])[0]));
            $CMonth = [
                'January'   => '01',    'Januari'   => '01',
                'February'  => '02',    'Februari'  => '02',
                'March'     => '03',    'Maret'     => '03',
                'April'     => '04',    'April'     => '04',
                'May'       => '05',    'Mei'       => '05',
                'June'      => '06',    'Juni'      => '06',
                'July'      => '07',    'Juli'      => '07',
                'August'    => '08',    'Agustus'   => '08',
                'September' => '09',    'September' => '09',
                'October'   => '10',    'Oktober'   => '10',
                'November'  => '11',    'November'  => '11',
                'December'  => '12',    'Desember'  => '12',
            ];

            if(!explode('">',explode('</span>',$data1)[0])[1]) {
                return ['result' => false,'data' => null,'message' => 'No result found for '.$bill.'.'];
            } else {
                $sdate = explode(' ',explode(' </td>',explode('</strong> ',$detail[3])[1])[0]);
                $sdate = $sdate[2].'-'.strtr($sdate[1],$CMonth).'-'.$sdate[0];

                $rdate = explode(' ',explode(' </td>',explode('</strong> ',$detail[4])[1])[0]);
                $rdate = $rdate[2].'-'.strtr($rdate[1],$CMonth).'-'.$rdate[0];

                $tracking = explode('<li class="timeline-inverted"> ',explode(' </ul>',explode('<ul class="timeline"> ',$data2)[1])[0]);
                for($i = 1; $i <= count($tracking)-1; $i++) {
                    $title = str_replace('Di','-',explode('</h4>',explode('<h4 class="timeline-title">',$tracking[$i])[1])[0]);
                    $body = explode(' </p>',explode('<div class="timeline-body"> <p> ',$tracking[$i])[1])[0];
                    $track[] = [
                        'date' => substr(explode('</small>',explode('<i class="fa fa-clock-o"></i> ',$tracking[$i])[1])[0],0,16),
                        'desc' => stristr($body,'</p> <small ') ? str_replace('- ','',$title) : "$body $title",
                    ];
                }

                return ['result' => true,'data' => [
                    'courier' => 'TIKI',
                    'waybill' => $bill,
                    'service' => explode('">',explode('</span>',$data1)[0])[1],
                    'shipped' => [
                        'name' => explode('</strong>',explode('</strong> <strong>',$detail[1])[1])[0],
                        'addr' => explode('</small>',explode('<small>',$detail[1])[1])[0],
                        'date' => $sdate
                    ],
                    'received' => [
                        'name' => explode('</strong>',explode('</strong> <strong>',$detail[2])[1])[0],
                        'recipient' => str_replace([explode(' / ',explode(' <i ',explode('</i> ',$data1)[1])[0])[0],' RECEIVED BY:',' / '],'',explode(' <i ',explode('</i> ',$data1)[1])[0]),
                        'addr' => explode('</small>',explode('<small>',$detail[2])[1])[0],
                        'date' => $rdate
                    ],
                    'tracking' => $track
                ],'message' => 'Successfully tracked package.'];
            }
        }
    }

    public function AnterAja($bill) { // 10000005008205
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $ch = $this->connectHeader('https://anteraja.id/api/api/trackingv2/trackparcel/getTrackStatus',['Content-Type: application/json'],'[{"awb":"'.$bill.'"}]');
            $ch = json_decode($ch[0], true);
            $detail = $ch['detail'];

            if(isset($detail['shipped_date'])) {
                $tracking = $ch['history'];
                for($i = 1; $i <= count($tracking)-1; $i++) {
                    $redate = date('Y-m-d', strtotime(explode(' ',$tracking[$i]['date_time'])[0]));
                    $track[] = [
                        'date' => substr(str_replace(explode(' ',$tracking[$i]['date_time'])[0],$redate,$tracking[$i]['date_time']),0,16),
                        'desc' => (isset($tracking[$i]['city_name'])) ? $tracking[$i]['city_name'].' - '.$tracking[$i]['status'] : $tracking[$i]['status'],
                    ];
                }

                $resdate = date('Y-m-d', strtotime(explode(' ',$detail['shipped_date'])[0]));
                $rerdate = date('Y-m-d', strtotime(explode(' ',$detail['delivered_date'])[0]));

                return ['result' => true,'data' => [
                    'courier' => 'AnterAja',
                    'waybill' => $bill,
                    'service' => $detail['services_code'],
                    'shipped' => [
                        'name' => $detail['sender']['name'],
                        'addr' => $detail['sender']['addr'].' ['.$detail['sender']['zipcode'].']',
                        'date' => substr(str_replace(explode(' ',$detail['shipped_date'])[0],$resdate,$detail['shipped_date']),0,16)
                    ],
                    'received' => [
                        'name' => $detail['receiver']['name'],
                        'recipient' => $detail['receiver']['name'],
                        'addr' => $detail['receiver']['addr'].' ['.$detail['receiver']['zipcode'].']',
                        'date' => substr(str_replace(explode(' ',$detail['delivered_date'])[0],$rerdate,$detail['delivered_date']),0,16)
                    ],
                    'tracking' => $track
                ],'message' => 'Successfully tracked package.'];
            } else {
                return ['result' => false,'data' => null,'message' => 'No result found for '.$bill.'.'];
            }
        }
    }

    public function WAHANA($bill) { // ABY25550
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $ch = $this->format('','https://www.wahana.com/lacak-kiriman?noresi='.$bill);

            $pusdata = explode('</div> </div> </div> <div class="col-12 col-md-4 mt-25 ">',explode('lacakkirimanresult"> <div id="accordion" class="accordion">',$ch)[1])[0];
            $main = explode('<div id="collapse0" class="card-body py-0 panel-collapse collapse bgmain-verylighgrey textmain-lightgrey show" data-parent="#accordion" >',$pusdata);

            if($main[0] == ' ') {
                return ['result' => false,'data' => null,'message' => 'No result found for '.$bill.'.'];
            } else {
                $rplc = ['font-size10 font-light','font-size22 font-bold','font-size12 font-light','font-size14 font-bold lh-16'];
                $detl = explode('<div class="ShennData"> ',str_replace($rplc,'ShennData',$main[0]));

                $trc = explode('<div class="row">',explode('<div class="col-12 col-md-9 pb-3 align-self-center">',$main[1])[1]);
                for($i = 2; $i <= count($trc)-1; $i++) {
                    $track[] = [
                        'date' => substr(explode(' </div>',explode('<div class="font-size12 font-light"> ',$trc[$i])[1])[0], 0, 16),
                        'desc' => explode(' </div>',explode('<div class="font-size14 font-bold lh-16"> ',$trc[$i])[1])[0],
                    ];
                }

                return ['result' => true,'data' => [
                    'courier' => 'WAHANA Express',
                    'waybill' => $bill,
                    'shipped' => [
                        'name' => explode(' </div>',$detl[5])[0],
                        'phone' => explode(' </div>',$detl[6])[0],
                        'addr' => explode(' </div>',$detl[11])[0],
                        'date' => $track[count($track) - 1]['date']
                    ],
                    'received' => [
                        'name' => explode(' </div>',$detl[8])[0],
                        'recipient' => ucwords(strtolower(str_replace('Diterima oleh ','',explode(' </div>',$detl[14])[0]))),
                        'phone' => explode(' </div>',$detl[9])[0],
                        'addr' => explode(' </div>',$detl[13])[0],
                        'date' => substr(explode(' </div>',$detl[15])[0], 0, 16)
                    ],
                    'tracking' => $track
                ],'message' => 'Successfully tracked package.'.$main];
            }
        }
    }

    public function Ninja($bill) { // NVIDBLAPK181037695932
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $ch = json_decode(file_get_contents('https://api.ninjavan.co/id/dash/1.2/public/orders?tracking_id='.$bill), true);
            if(isset($ch['error']['message']) || !$ch) {
                if(isset($ch['error']['message'])) {
                    return ['result' => false,'data' => null,'message' => $ch['error']['message']];
                } else {
                    return ['result' => false,'data' => null,'message' => 'No result found for '.$bill.'.'];
                }
            } else {
                for($i = 0; $i <= count($ch['events'])-1; $i++) {
                    $events = $ch['events'][$i];
                    $typels = strtr($events['type'],[
                        'FROM_SHIPPER_TO_DP' => 'Parcel dropped off at Parcel Dropoff Counter / Box',
                        'DRIVER_PICKUP_SCAN' => 'Successfully picked up from sender',
                        'FROM_DP_TO_DRIVER' => 'Departed Parcel Dropoff Counter / Box',
                        'ROUTE_INBOUND_SCAN' => 'Parcel is being processed at Ninja Van warehouse',
                        'ADDED_TO_SHIPMENT' => 'Departed Ninja Van warehouse - In Transit',
                        'PARCEL_ROUTING_SCAN' => 'Parcel is being processed at Ninja Van warehouse',
                        'DRIVER_INBOUND_SCAN' => 'Parcel is being delivered',
                        'DELIVERY_FAILURE' => 'Delivery is unsuccessful - For further assistance, kindly contact support_id@ninjavan.co',
                        'RESCHEDULE' => 'Parcel delivery has been rescheduled',
                        'DELIVERY_SUCCESS' => 'Successfully delivered',
                        'HUB_INBOUND_SCAN' => 'Parcel is being processed at Ninja Van warehouse',
                        'RTS' => 'Parcel is being returned to sender',
                        'DELIVERY_FAILURE' => 'Delivery is unsuccessful'
                    ]);

                    if(isset($events['data']['failure_reason']['en'])) {
                        $desc = $typels.' - '.$events['data']['failure_reason']['en'];
                    } else {
                        if($events['type'] == 'DRIVER_PICKUP_SCAN') {
                            $desc = $typels.' - '.$ch['shipper_short_name'];
                        } else {
                            $desc = (isset($events['data']['hub_name'])) ? $typels.' - '.ucwords(str_replace('_',' ',$events['data']['hub_name'])) : $typels;
                        }
                    }

                    $track[] = [
                        'date' => date('Y-m-d H:i', strtotime('+7 hours', strtotime(substr(str_replace(['T','Z'],' ',$events['time']),0,19)))),
                        'desc' => $desc
                    ];
                }

                return ['result' => true,'data' => [
                    'courier' => 'Ninja Xpress',
                    'waybill' => $bill,
                    'service' => $ch['service_type'],
                    'shipped' => [
                        'name' => $ch['shipper_short_name'],
                        'date' => $track[0]['date']
                    ],
                    'received' => [
                        'name' => $ch['pods'][0]['name'],
                        'sign' => $ch['pods'][0]['url'],
                        'date' => $track[count($track) - 1]['date']
                    ],
                    'tracking' => $track,
                    'status' => $ch['status']
                ],'message' => 'Successfully tracked package.'];
            }
        }
    }

    public function Lion($bill) { // 11-19-5470676
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $ch = $this->format('','http://lionparcel.com/track?q='.$bill);
            $main = explode('</div> <div class="accordion-body has-background-white">',explode(' </section>',explode('<section class="accordions font-regular"> ',$ch)[1])[0]);
            $CMonth = [
                ' Jan '  => '-01-'.substr(date('Y'),0,2),  ' July ' => '-07-'.substr(date('Y'),0,2),
                ' Feb '  => '-02-'.substr(date('Y'),0,2),  ' Aug '  => '-08-'.substr(date('Y'),0,2),
                ' Mar '  => '-03-'.substr(date('Y'),0,2),  ' Sept ' => '-09-'.substr(date('Y'),0,2),
                ' Apr '  => '-04-'.substr(date('Y'),0,2),  ' Oct '  => '-10-'.substr(date('Y'),0,2),
                ' May '  => '-05-'.substr(date('Y'),0,2),  ' Nov '  => '-11-'.substr(date('Y'),0,2),
                ' June ' => '-06-'.substr(date('Y'),0,2),  ' Dec '  => '-12-'.substr(date('Y'),0,2),
            ];

            $status = explode('</small> ',explode(' <small>',$main[0])[1])[0];
            $tracking = explode('<li> <article class="media is-vcentered"> ',explode(' </ul> </div> </div> </article>',explode('<div class="accordion-content"> <ul> ',$main[1])[1])[0]);

            if(preg_match('/\<li\> \<img src="(.*?)" \/\> \<\/li\>/',$tracking[0])) {
                return ['result' => false,'data' => null,'message' => $status.'.'];
            } else {
                for($i = 1; $i <= count($tracking)-1; $i++) {
                    $date = strtr(explode('</div>',explode('<div class="has-text-danger">',$tracking[$i])[1])[0],$CMonth);
                    $track[] = [
                        'date' => date('Y-m-d', strtotime(explode(' ',$date)[0])).' '.explode(' ',$date)[1],
                        'desc' => explode(' </div>',explode('<div class="column"> ',$tracking[$i])[1])[0],
                        'icon' => 'http://lionparcel.com/'.explode('"> ',explode(' <img src="',$tracking[$i])[1])[0]
                    ];
                }

                return ['result' => true,'data' => [
                    'courier' => 'Lion Parcel',
                    'waybill' => $bill,
                    'stt_number' => explode('</strong></p>',explode('<p>STT Number: <strong>',$main[0])[1])[0],
                    'shipped' => [
                        'name' => str_replace(['BOOKED BY ',' .'],'',$track[0]['desc']),
                        'date' => $track[0]['date']
                    ],
                    'received' => $track[count($track)-1]['date'],
                    'tracking' => $track,
                    'status' => $status
                ],'message' => 'Successfully tracked package.'];
            }
        }
    }

    public function LEX($bill,$lang = 'en') { // LXRP-9031707489 // en or id
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $acclang_en = ['en','english','inggris','us']; $acclang_id = ['id','indonesia','indo','indonesian']; $acclang_my = ['my','malaysia','malaysian','melayu','ms'];
            $acclang_th = ['th','thailand','thai']; $acclang_vn = ['vi','vietnam','viet','vn'];
            if(in_array($lang,array_merge($acclang_en,$acclang_id))) {
                $lang = (in_array($lang,$acclang_en)) ? 'en-US' : 'id-ID';
                $stype = ($lang == 'en-US')  ? 'Customer Order Creation' : 'Pembuatan Pesanan Pelanggan';
                $dtype = ($lang == 'en-US')  ? 'Delivery Type' : 'Jenis pengiriman';
                $lname = ($lang == 'en-US')  ? 'English' : 'Indonesian';
            } else if(in_array($lang,array_merge($acclang_my,$acclang_th))) {
                $lang = (in_array($lang,$acclang_my)) ? 'ms-MY' : 'th-TH';
                $stype = ($lang == 'ms-MY')  ? 'Pembuatan Pesanan Pelanggan' : '?????????????????????????????????????????????';
                $dtype = ($lang == 'ms-MY')  ? 'Jenis Penghantaran' : '?????????????????????';
                $lname = ($lang == 'ms-MY')  ? 'Malaysia' : 'Thailand';
            } else if(in_array($lang,$acclang_vn)) {
                $lang = 'vi-VN';
                $stype = 'Ng�y kh�ch h�ng t?o ??n h�ng';
                $dtype = 'H�nh th?c giao h�ng';
                $lname = 'Vietnam';
            } else {
                $lang = 'en-US';
                $stype = 'Customer Order Creation';
                $dtype = 'Delivery Type';
                $lname = 'English';
            }

            $ch = $this->format('','https://tracker.lel.asia/tracker?lang='.$lang.'&trackingNumber='.$bill);
            $main = explode('<div class="container"> ',explode(' </div> <script type="text/javascript" src="public/js/main.js"></script>',explode('<div class="wrapper"> ',$ch)[1])[0]);

            $CMonth_en = [' Jan' => '-01',' July' => '-07',' Feb' => '-02',' Aug' => '-08',' Mar' => '-03',' Sept' => '-09',' Apr' => '-04',' Oct' => '-10',' May' => '-05',' Nov' => '-11',' June' => '-06',' Dec' => '-12'];
            $CMonth_id = [' Jan' => '-01',' Jul' => '-07',' Des' => '-12',' Feb' => '-02',' Agu' => '-08',' Mar' => '-03',' Ags' => '-08',' Apr' => '-04',' Sep' => '-09',' Mei' => '-05',' Okt' => '-10',' Jun' => '-06',' Nov' => '-11',];
            $CMonth_ms = [' Jan' => '-01',' Jul' => '-07',' Feb' => '-02',' Ogo' => '-08',    ' Mac' => '-03',' Sep' => '-09',' Apr' => '-04',' Okt' => '-10',' Mei' => '-05',' Nov' => '-11',' Jun' => '-06',' Dis' => '-12'];
            $CMonth_th = [' ??????' => '-01',' ??????????' => '-02',' ??????' => '-03',' ??????' => '-04',' ???????' => '-05',' ????????' => '-06',' ???????' => '-07',' ???????' => '-08', ' ???????' => '-09',' ??????' => '-10',' ?????????' => '-11',' ???????' => '-12'];
            $CMonth_vn = [' Th�ng 1' => '-01',' Th�ng 7' => '-07',' Th�ng 2' => '-02',' Th�ng 8'  => '-08',    ' Th�ng 3' => '-03',' Th�ng 9'  => '-09',' Th�ng 4' => '-04',' Th�ng 10' => '-10',' Th�ng 5' => '-05',' Th�ng 11' => '-11',' Th�ng 6' => '-06',' Th�ng 12' => '-12'];
            $CMonth = array_merge(array_merge(array_merge($CMonth_en,$CMonth_id),array_merge($CMonth_ms,$CMonth_th)),$CMonth_vn);

            $error = explode('</h4>',explode('<div class="error-message"><h4>',$ch)[1])[0];
            if(!$main[0]) {
                return ['result' => false,'data' => null,'message' => $error.'.'];
            } else {
                $shipped = date('Y-m-d',strtotime(str_replace(' ','-',strtr(explode(' </span>',explode($stype.': </span> <span class="details__value"> ',$main[2])[1])[0],$CMonth))));
                $tracking = explode('<div class="row trace__date_row"> ',$main[3]);
                for($i = 1; $i <= count($tracking)-1; $i++) {
                    $date = strtr(explode(' <div ',str_replace('</div> </div> ','',explode('<div class="trace__date _done"> ',$tracking[$i])[1]))[0],$CMonth).'-'.explode('-',$shipped)[0];
                    $date = date('Y-m-d',strtotime($date));
                    $table = explode('<tr class="trace__item"> ',$tracking[$i]);
                    for($t = 1; $t <= count($table)-1; $t++) {
                        $desc = explode('">',explode('</span> </td> </tr> ',$table[$t])[0]);
                        $track[] = [
                            'date' => $date.' '.explode('</span>',explode('<span class=".trace__time">',$table[$t])[1])[0],
                            'desc' => $desc[count($desc)-1]
                        ];
                    }
                }

                return ['result' => true,'data' => [
                    'courier' => 'Lazada eLogistics Express',
                    'waybill' => $bill,
                    'service' => ucwords(explode('</span>',explode($dtype.': </span> <span class="details__value">',$main[2])[1])[0]),
                    'shipped' => $shipped,
                    'received' => substr($track[0]['date'],0,10),
                    'tracking' => $track,
                    'status' => explode(' <span ',explode(' _current\'> ',$main[1])[1])[0],
                    'language' => $lname
                ],'message' => 'Successfully tracked package.'];
            }
        }
    }

    public function JNE($bill) { // 542630035865718
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            $header = ['Content-Type: application/x-www-form-urlencoded'];
            $postdata = http_build_query(['username' => 'JNEONE','api_key' => '504fbae0d815bf3e73a7416be328fcf2']);
            $this->useProxy = 'true';
            $ch = $this->connectHeader('http://apiv2.jne.co.id:10101/tracing/api/list/myjne/cnote/'.$bill,$header,$postdata);

            if(!isset($ch['cnote']) && !isset($ch['detail']) && !isset($ch['history'])) {
                return ['result' => false,'data' => null,'message' => $ch['error']];
            } else {
                $tracking = $ch['history'];
                for($i = 1; $i <= count($tracking)-1; $i++) {
                    $track[] = [
                        'date' => date('Y-m-d', strtotime(explode(' ', $tracking[$i]['date'])[0])).' '.explode(' ', $tracking[$i]['date'])[1],
                        'desc' => $tracking[$i]['desc'],
                    ];
                }
                $cnote = $ch['cnote'];
                $detail = $ch['detail'][0];

                return ['result' => true,'data' => [
                    'courier' => 'JNE Express',
                    'waybill' => $bill,
                    'service' => $cnote['cnote_services_code'],
                    'shipped' => [
                        'name' => $detail['cnote_shipper_name'],
                        'addr' => $detail['cnote_shipper_addr1'].$detail['cnote_shipper_addr2'].$detail['cnote_shipper_addr3'],
                        'city' => $detail['cnote_shipper_city'],
                        'date' => substr(str_replace(['T','Z','.'],' ',$cnote['cnote_date']),0,16)
                    ],
                    'received' => [
                        'name' => $detail['cnote_receiver_name'],
                        'recipient' => $cnote['cnote_pod_receiver'],
                        'addr' => $detail['cnote_receiver_addr1'].$detail['cnote_receiver_addr2'].$detail['cnote_receiver_addr3'],
                        'city' => $detail['cnote_receiver_city'],
                        'date' => $track[count($track)-1]['date']
                    ],
                    'tracking' => $track,
                    'status' => $cnote['pod_status']
                ],'message' => 'Successfully tracked package.'];
            }
        }
    }

    public function PosIndonesia($bill) { // 17648656810
        if(!$bill) {
            return ['result' => false,'data' => null,'message' => 'The billcode field is required.'];
        } else {
            //$this->useProxy = 'true';
            $try = $this->connectHeader('https://order.posindonesia.co.id/api/lacak',['Content-Type: application/json'],json_encode(['barcode' => $bill]));

            if(isset($try['result'])) {
                if($try['result'][0]['barcode'] == $bill) {
                    for($i = 0; $i <= count($try['result'])-1; $i++) {
                        $track[] = [
                            'date' => $try['result'][$i]['eventDate'],
                            'desc' => $try['result'][$i]['description'],
                        ];
                    }

                    return ['result' => true,'data' => [
                        'courier' => 'Pos Indonesia',
                        'waybill' => $bill,
                        'tracking' => $track
                    ],'message' => 'Successfully tracked package.'];
                } else {
                    return ['result' => false,'data' => null,'message' => 'Invalid billcode.'];
                }
            } else {
                return ['result' => false,'data' => null,'message' => isset($try['errors']['global']) ? $try['errors']['global'].'.' : 'System Error.'];
            }
        }
    }

    # END POINT CONNECTION

    private $useProxy = 'false';

    private function connect($url,$data,$reqout = 'decode') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($this->useProxy == 'true') curl_setopt($ch, CURLOPT_PROXY, 'proxy.rapidplex.com:3128');
        if($this->useProxy == 'true') curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'user:domainesia');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        curl_close($ch);
        if(!$chresult) $chresult = $this->connectHeader($url,['content-type: multipart/form-data;'],$data,'original');
        return ($reqout == 'decode') ? json_decode($chresult, true) : $chresult;
    }

    private function connectHeader($end_point,$header,$postdata,$reqout = 'decode') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $end_point);
        if($this->useProxy == 'true') curl_setopt($ch, CURLOPT_PROXY, 'proxy.rapidplex.com:3128');
        if($this->useProxy == 'true') curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'user:domainesia');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $chresult = curl_exec($ch);
        return ($reqout == 'decode') ? json_decode($chresult, true) : $chresult;
    }
}
$PT = new PackageTracking;
