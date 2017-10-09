//
//  JsBridge.m
//  vosANDgbuy
//
//  Created by apple on 2017/9/25.
//  Copyright © 2017年 LY. All rights reserved.
//

#import <WebKit/WebKit.h>
#import "JsBridge.h"

@interface JsBridge ()<WKScriptMessageHandler>
@property (nonatomic, weak)WKUserContentController * userContentController;
@end


@implementation JsBridge
- (instancetype)initWithUserContentController:(WKUserContentController *)userContentController{
    if (self = [super init]) {
        _userContentController = userContentController;
    }return self;
}
/// 注入JS MessageHandler和Name
- (void)setUserScriptNames:(NSArray *)userScriptNames{
    _userScriptNames = userScriptNames;
    [userScriptNames enumerateObjectsUsingBlock:^(NSString * _Nonnull obj, NSUInteger idx, BOOL * _Nonnull stop) {
        [self.userContentController addScriptMessageHandler:self name:obj];
    }];
}
/// 移除JS MessageHandler
- (void)removeAllUserScripts{
    [self.userScriptNames enumerateObjectsUsingBlock:^(NSString * _Nonnull obj, NSUInteger idx, BOOL * _Nonnull stop) {
        [self.userContentController removeScriptMessageHandlerForName:obj];
    }];
    self.userScriptNames = nil;
}
/// 接收JS调iOS的事件消息
- (void)userContentController:(WKUserContentController *)userContentController didReceiveScriptMessage:(WKScriptMessage *)message{
    
    NSLog(@"JS调iOS  name : %@    body : %@",message.name,message.body);

}




@end
