<?php
use Illuminate\Routing\Router;

Admin::routes();
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->resource('visualization', VisualizationController::class);
    $router->get('/', 'HomeController@index')->name('home')->middleware('check');
    $router->resource('customer', CustomerController::class);
    $router->resource('business', BusinessController::class);
    $router->get('api/getAreaNameByParentId', 'ApiController@getAreaNameByParentId');
    $router->get('api/getCustomerName', 'ApiController@getCustomerName');
    $router->get('api/getType', 'ApiController@getType');
    $router->resource('order', OrderController::class);
    $router->get('api/getBusinessByCustomerId', 'ApiController@getBusinessByCustomerId');
    $router->get('delOneDayDate', 'GatherController@delOneDayDate');
    $router->resource('gather', GatherController::class);
    $router->get('showData/setSumCache', 'ShowDataController@setSumCache');
    $router->resource('showData', ShowDataController::class);
    $router->get('auth/login', 'AuthController@getLogin')->name('admin.login')->middleware('check');
    $router->resource('meanDetail', MeanDetailController::class);
    $router->get('keywords/showData', 'KeywordsController@showData');
    $router->resource('keywords', KeywordsController::class);
    $router->resource('arithmetic_blacklist', ArithmeticBlackListController::class);
    $router->resource('rubbings', RubbingsController::class);
    $router->resource('messages', MessagesController::class);
    $router->get('static-web/test', 'StaticWebController@test');
    $router->resource('static-web', StaticWebController::class);
    $router->post('static-web/setStatic', 'StaticWebController@setStatic');
    $router->post('static-web/setWapStatic', 'StaticWebController@setWapStatic');
    $router->resource('phone-show', PhoneController::class);
    $router->resource('price_blacklist', Price_blacklistController::class);
    $router->resource('type', TypeController::class);
    $router->resource('article', ArticleController::class);
    $router->resource('seo', SeoController::class);
    $router->resource('nav', NavController::class);
    $router->resource('commodity', CommodityController::class);
    $router->get('tripartite_keywords/business_name_deal', 'TripartiteKeywordsController@business_name_deal');
    $router->resource('tripartite_keywords', TripartiteKeywordsController::class);
    $router->resource('tripartite_data', TripartiteDateController::class);
    $router->resource('tripartite_node', TripartiteNodeController::class);
    $router->resource('supplier', SupplierController::class);
    $router->get('orders/demo', 'OrderController@demo');
    $router->resource('order', OrderController::class);
    $router->resource('offerer', OffererController::class);
    $router->post('cdata/delAjax3_5', 'CdataController@delAjax3_5');
    $router->resource('cdata', CdataController::class);
    $router->get('fm_data/get_united_area_code', 'FmDataController@get_united_area_code');
    $router->resource('fm_data', FmDataController::class);
    $router->resource('bid_data', BidDataController::class);
    $router->get('united/get_united_area_code', 'UnitedController@get_united_area_code');
    $router->resource('united', UnitedController::class);
    $router->resource('standard', Standard2Controller::class);
    $router->resource('question', QuestionController::class);
    $router->resource('quote', QuoteController::class);
    $router->resource('webchat', WebchatController::class);
    $router->resource('webroles', WebRolesController::class);
    $router->get('/chat-web/my_iframe', 'ChatWebController@my_iframe');
    $router->resource('chat-web', ChatWebController::class);
    $router->resource('call', CallController::class);
    $router->resource('report', ReportController::class);
    $router->resource('getDetection', GetDetectionController::class);
    $router->resource('call_deal', CallDealController::class);
    $router->post('call_deal/upload_excel', 'CallDealController@upload_excel');
    $router->post('record/uploadFiles', 'RecordFileController@uploadFiles');
    $router->resource('recordfile', RecordFileController::class);
    $router->resource('construction', ConstructionController::class);
    $router->resource('source', SourceController::class);
    $router->resource('split', SplitController::class);
    $router->resource('private_sphere', PrivateSphereController::class);
    $router->resource('data', DataController::class);
    $router->resource('tripartite', TripartiteController::class);
    $router->resource('detection-list', DetectionListController::class);
    $router->resource('detection_tripartite_node', DetectionTripartiteNodeController::class);
    $router->resource('detection_keywords', DetectionKeywordsController::class);
    $router->resource('detection_data', DetectionDataController::class);
    $router->post('supplier/getOffererAjax', 'SupplierController@getOffererAjax');
    $router->post('supplier/offererCommodity', 'SupplierController@offererCommodity');
    $router->post('offerer/getOffererAjax', 'OffererController@getOffererAjax');
    $router->post('offerer/offererCommodity', 'OffererController@offererCommodity');
    $router->resource('tripartite_offerer', TripartiteOffererController::class);
    $router->resource('tripartite_supplier', TripartiteSupplierController::class);
    $router->resource('plugging_node', PluggingNodeController::class);
    $router->resource('plugging_keywords', PluggingKeywordsController::class);
    $router->resource('plugging_data', PluggingDataController::class);
    $router->resource('plugging_supplier', PluggingSupplierController::class);
    $router->resource('plugging_offerer', PluggingOffererController::class);
    $router->resource('supplier_blacklist', SupplierBlacklistController::class);
    $router->resource('snapshot', SnapshotController::class);
    $router->resource('fissure', FissureController::class);
    $router->post('commodity/setCommodityStaticById', 'CommodityController@setCommodityStaticById');

});
