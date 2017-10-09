//
//  ViewController.m
//  vosANDgbuy
//
//  Created by apple on 2017/9/21.
//  Copyright © 2017年 LY. All rights reserved.
//

#import "ViewController.h"

#import "WebviewProgressLine.h"

#import "VDUrlRequest.h"

#import "WKWebViewController.h"

@interface ViewController ()<UIWebViewDelegate>

@property (nonatomic, strong) WebviewProgressLine * progressLine;

@property (nonatomic, strong) UIWebView * webView;


@property (nonatomic, strong) UIView * backView;


@property (nonatomic, strong) VDUrlRequest * urlRequest;



@end

@implementation ViewController

-(UIView *)backView
{
    if (_backView == nil) {
        _backView = [[UIView alloc]initWithFrame:CGRectMake(0, 0, kWindowWidth, kWindowHeigth)];
        
        _backView.backgroundColor =[UIColor whiteColor];
        
    }
    return _backView;
    }
- (void)viewDidLoad {
    [super viewDidLoad];
    
    self.view.backgroundColor = [UIColor whiteColor];

    if (@available(iOS 11.0, *)) {
        self.additionalSafeAreaInsets = UIEdgeInsetsMake(-20, 0, 0, 0);
    } else {
        // Fallback on earlier versions
    }
    
    UIWebView * webView = [[UIWebView alloc]init];

    webView.delegate = self;

    
    webView.frame = CGRectMake(0, 0, self.view.frame.size.width, self.view.frame.size.height );

    [webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:@"http://shop.liwus.de/gbuy/#homeTop"]]];

//    [webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:@"https://www.baidu.com"]]];
    
    self.webView = webView;
    self.progressLine = [[WebviewProgressLine alloc] initWithFrame:CGRectMake(0, 0, kWindowWidth, 3)];
    self.progressLine.lineColor = [UIColor greenColor];
    
    [self.view addSubview:self.webView];

    [self.view addSubview:self.progressLine];
    
//
//    UIButton * btn = [UIButton buttonWithType:UIButtonTypeCustom];
//    btn.frame = CGRectMake(100, 100, 100, 100);
//
//    [btn setTitle:@"点击跳转WkWebView" forState:0];
//
//    [btn addTarget:self action:@selector(clickmodelVc) forControlEvents:UIControlEventTouchUpInside];
//
//
//    [btn setBackgroundColor:[UIColor blackColor]];
//
//    [self.view addSubview:btn];
    
    
}

-(void)clickmodelVc
{
    
    WKWebViewController * webView = [[WKWebViewController alloc]init];
    
//    UINavigationController * navVC = [[UINavigationController alloc]initWithRootViewController:webView];
    

    [self.navigationController pushViewController:webView animated:YES];
    
//    [self presentViewController:webView animated:YES completion:^{
    
//    }];
}



-(void)webViewDidStartLoad:(UIWebView *)webView
{
//    NSLog(@"%@",webView.request.URL);
    
    [self.progressLine startLoadingAnimation];

}

-(void)webViewDidFinishLoad:(UIWebView *)webView
{
    [self.progressLine endLoadingAnimation];

//    NSLog(@"%@",webView.request.URL);

}

-(BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType
{
//    NSLog(@"%@",request.URL);
    
    return YES;
}

-(void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error
{
//    NSLog(@"%@-------%@",webView.request.URL,error);
    [self.progressLine endLoadingAnimation];

}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];


}


@end
