
//
//  VDUrlRequest.m
//  vosANDgbuy
//
//  Created by apple on 2017/9/25.
//  Copyright © 2017年 LY. All rights reserved.
//

#import "VDUrlRequest.h"

@implementation VDUrlRequest

+(void)load
{
    [NSURLProtocol registerClass:self];
    
}

+(BOOL)canInitWithRequest:(NSURLRequest *)request
{
    if ([request isKindOfClass:[NSMutableURLRequest class]]) {
//        NSLog(@"%@",request);
        
//        NSDictionary * dic = [NSJSONSerialization JSONObjectWithData:request options:NSJSONReadingMutableContainers error:nil];

//        NSLog(@"%@",dic);
        
        
        
    }
    return NO;
}



@end
