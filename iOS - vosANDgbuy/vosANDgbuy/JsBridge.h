//
//  JsBridge.h
//  vosANDgbuy
//
//  Created by apple on 2017/9/25.
//  Copyright © 2017年 LY. All rights reserved.
//

#import <Foundation/Foundation.h>

#import <WebKit/WebKit.h>

@interface JsBridge : NSObject

@property (nonatomic, strong) NSArray * userScriptNames;

@property (nonatomic, strong) UIViewController * webViewController;

@property (nonatomic, strong) WKWebView * webView;


- (void)removeAllUserScripts;

- (instancetype)initWithUserContentController:(WKUserContentController *)userContentController;
@end
