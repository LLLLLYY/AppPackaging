//
//  WKWebViewController.m
//  vosANDgbuy
//
//  Created by apple on 2017/9/25.
//  Copyright © 2017年 LY. All rights reserved.
//

#import "WKWebViewController.h"

#import "JsBridge.h"

#import <WebKit/WebKit.h>
@interface WKWebViewController ()<WKUIDelegate,WKNavigationDelegate>


@property (nonatomic, strong) WKWebView * webView;

@property (nonatomic, strong) JsBridge * jsBridge;

@property (nonatomic, strong) WKNavigation * gobackNavigation;


@end

@implementation WKWebViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view.
    
    if (@available(iOS 11.0, *)) {
        self.additionalSafeAreaInsets = UIEdgeInsetsMake(-20, 0, 0, 0);
    } else {
        // Fallback on earlier versions
    }
    
    WKWebViewConfiguration * configuration = [[WKWebViewConfiguration alloc] init];
    configuration.preferences = [[WKPreferences alloc]init];
    configuration.preferences.javaScriptEnabled = YES;
    configuration.preferences.javaScriptCanOpenWindowsAutomatically = NO;
    configuration.processPool = [[WKProcessPool alloc]init];
    configuration.allowsInlineMediaPlayback = YES;
    //    if (iOS9()) {
    //        /// 缓存机制(未研究)
    //        configuration.websiteDataStore = [WKWebsiteDataStore defaultDataStore];
    //    }
    configuration.userContentController = [[WKUserContentController alloc] init];
    WKWebView * webView = [[WKWebView alloc]initWithFrame:CGRectMake(0, 0, kWindowWidth, kWindowHeigth) configuration:configuration];
    self.webView = webView;

    /// 侧滑返回上一页，侧滑返回不会加载新的数据，选择性开启
    self.webView.allowsBackForwardNavigationGestures = YES;
    /// 在这个代理相应的协议方法可以监听加载网页的周期和结果
    self.webView.navigationDelegate = self;
    /// 这个代理对应的协议方法常用来显示弹窗
    self.webView.UIDelegate = self;
    /// 如果涉及到JS交互，比如Web通过JS调iOS native，最好在[webView loadRequest:]前注入JS对象，详细代码见文章后半部分代码。
    self.jsBridge = [[JsBridge alloc]initWithUserContentController:configuration.userContentController];
    self.jsBridge.webView = webView;
    self.jsBridge.webViewController = self;
    
    
    [self.webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:@"https://www.apple.com/cn/"]]];
    
    [self.view addSubview:self.webView];
    
}

-(void)webView:(WKWebView *)webView didStartProvisionalNavigation:(WKNavigation *)navigation
{
    
    NSLog(@"%@",webView.URL);
    
}
-(void)webView:(WKWebView *)webView didReceiveServerRedirectForProvisionalNavigation:(WKNavigation *)navigation
{
    NSLog(@"%@",webView.URL);
    
}
-(void)webView:(WKWebView *)webView didFinishNavigation:(WKNavigation *)navigation
{
    NSLog(@"%@",webView.URL);
    
}


- (void)reloadWebViewWithUrl:(NSString *)url backToHomePage:(BOOL)backToHomePage{
    void (^LoadWebViewBlock)(void) = ^() {
        /// 每次加载新url前重新注入JS对象
        [self.jsBridge removeAllUserScripts];
//        self.jsBridge.userScriptNames = @[kJS_Login,kJS_Logout,kJS_Alipay,kJS_WeiChatPay,kJS_Location];
//        self.urlStr = url;
//        [self.webView loadRequest:[[NSURLRequest alloc] initWithURL:self.urlStr.URL]];
    };
    if (self.webView.backForwardList.backList.count && backToHomePage) {
        /// 返回首页再跳转,并且保留WKNavigation对象
        self.gobackNavigation = [self.webView goToBackForwardListItem:self.webView.backForwardList.backList.firstObject];
        /// 延迟加载新的url，否则报错-999
//        [self excuteDelayTask:0.5 InMainQueue:^{
//            LoadWebViewBlock();
//        }];
    } else {
        LoadWebViewBlock();
    }
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
