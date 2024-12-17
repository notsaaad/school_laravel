    <style>
        #loader {
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: 9;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            display: none;
        }

        #loader {
            font-size: 40px;
            color: var(--mainBg);
        }



        #loader i {
            font-size: 50px;
        }

        @media(max-width:993px) {
            table:not(.ssi-imgToUploadTable) td {
                padding: .7rem;
                font-size: 14px;
            }
        }

        .prodcut_img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 5px;

        }

        .frame table td {
            padding: 5px 5px !important;
            font-size: 13px !important;
        }



        .frame {
            background: rgba(0, 0, 0, .32);
            position: fixed;
            top: 0px;
            left: 0px;
            width: calc(100%);
            height: 100%;
            z-index: 9999999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .frameContent {
            box-shadow: 0 11px 15px -7px #0003, 0 24px 38px 3px #00000024, 0 9px 46px 8px #0000001f;
            background: #fff;
            border-radius: 4px;
            width: 70%;
            padding: 20px;
            overflow-y: scroll
        }

        @media (max-width:993px) {
            .frame {
                width: 100%;
            }

            .frameContent {
                width: 92vw;

            }
        }


        .frameHead {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }




        hr {

            opacity: .1
        }

        .group {
            display: flex;
            flex-direction: column;
        }

        .value {
            color: var(--mainBg);
            font-size: 14px;
            font-weight: 600;
        }
    </style>


    <div class="frame" id="frame" style="display: none ">


        <div style="background-color: #f8f8f8" class="frameContent" onclick="event.stopPropagation();">
            <div class="frameHead">

                <div class="group">
                    <div class="value" id="reference"></div>
                </div>

                <div class="group">
                    <div id="status" class=" orderStatus "> </div>
                </div>
            </div>

            <hr>

            <p style="font-size: 20px; font-weight: 600">{{ trans('words.المنتجات') }} </p>

            <div style="overflow-x: scroll">

                <table>
                    <thead>
                        <th>{{ trans('words.صورة المنتج') }}</th>
                        <th>{{ trans('words.اسم المنتج') }} </th>
                        <th><strong>{{ trans('words.الكمية') }}</strong></th>
                    </thead>

                    <tbody id="frameTable">


                    </tbody>
                </table>

            </div>
        </div>
    </div>
